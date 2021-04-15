<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use PhpImap as PhpImap;
use PhpImap\Mailbox as MailBox;
use function Stringy\create as s;


class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function home()
    {
    }

    public function testClient()
    {
        $imap_settings = config('enums.IMAP');
        $client = new MailClient($imap_settings['GMAIL']['USER'], $imap_settings['GMAIL']['PASSWORD'], "INBOX");
        $client->connect();
        $arrMails = $client->fetchWith('ALL SINCE "8 April 2019" FROM "akash.saikia89@gmail.com"', 'YOURHIRED-');
        $data = $client->getValuesByKeywords($arrMails, ["Phone:", "Name:"]);
        echo json_encode($data);
        // print_r($data);
    }

    public function sendApiResponse($result, $message = '', $code = 200)
    {
        $response = [
            'status'    => array(
                'code' => $code,
                'error' => false,
                'message'   => $message
            ),
        ];

        if (!is_null($result)) {
            $response['data'] = $result;
        }

        return response($response, $code);
    }
    public function sendApiError($message, $data = null, $code = 404)
    {
        $response = [
            'status'    => array(
                'code' => $code,
                'error' => true,
                'message'   => $message
            ),
        ];

        if (!empty($data)) {
            $response['data'] = $data;
        }
        return response($response, $code);
    }

    public function getApiCountries()
    {
        $endpoint = "https://api.countrystatecity.in/v1/countries";

        $headers = [
            'Content-Type' => 'application/json',
            'X-CSCAPI-KEY' => 'NUlkeFZrNzBBVEtYSDhvZjFqUkR2VjVuVnVDOTl4cEJ6Sm5DSE9veA==',
            // 'Authorization' => 'Bearer token',
        ];
        $client = new \GuzzleHttp\Client([
            'headers' => $headers
        ]);
        $response = $client->request('GET', $endpoint);
        $statusCode = $response->getStatusCode();
        $content = $response->getBody()->getContents();
        return $content;
    }

    public function getApiCitiesByCountry($code)
    {
        $endpoint = "https://api.countrystatecity.in/v1/countries/" . $code . "/cities";
        $headers = [
            'Content-Type' => 'application/json',
            'X-CSCAPI-KEY' => 'NUlkeFZrNzBBVEtYSDhvZjFqUkR2VjVuVnVDOTl4cEJ6Sm5DSE9veA==',
            // 'Authorization' => 'Bearer token',
        ];
        $client = new \GuzzleHttp\Client([
            'headers' => $headers
        ]);
        $response = $client->request('GET', $endpoint);
        $statusCode = $response->getStatusCode();
        $content = $response->getBody()->getContents();
        return $content;
    }
}

class MailClient
{
    private $username;
    private $password;
    protected $readFolder;
    protected $fetchCondition;
    protected $imapAddress = "{imap.gmail.com:993/imap/ssl}";
    protected $mailBox;
    protected $isConnected = false;

    private $splitChainsWith = "--Reply above this line--";

    public function __construct($username = null, $password = null, $readFolder = "INBOX")
    {
        $this->username = $username;
        $this->password = $password;
        $this->readFolder = $readFolder;
    }
    public function connect()
    {
        $this->isConnected = true;
        $this->disconnect();
        $this->mailBox = new Mailbox(
            $this->imapAddress, // IMAP server and mailbox folder
            $this->username, // Username for the before configured mailbox
            $this->password, // Password for the before configured username
            $attachmentsDir = null, // Directory, where attachments will be saved (optional)
            $serverEncoding = 'UTF-8' // Server encoding (optional)
        );

        return $this->mailBox->statusMailbox();
    }

    public function setImapAddress($imapAddress)
    {
        return $this->imapAddress = $imapAddress;
    }
    public function getImapAddress()
    {
        return $this->imapAddress;
    }
    public function disconnect()
    {
        $this->isConnected = false;
        if ($this->mailBox) {
            $this->mailBox->disconnect();
            unset($this->mailBox);
        }
    }
    public function getMailBox()
    {
        return $this->mailBox;
    }
    public function fetchAll()
    {
    }
    public function fetchWith($imapCondition, $headCondition = null)
    {
        $mailbox = $this->mailBox;
        $mailIds = [];
        try {
            $mailIds = $mailbox->searchMailbox($imapCondition);
        } catch (PhpImap\Exceptions\ConnectionException $ex) {
            echo "IMAP connection failed: " . $ex;
            die();
        }
        $mailbox->disconnect();

        $arrMails = array();
        $markAsSeen = false;
        foreach ($mailIds as $num) {
            $head = $mailbox->getMailHeader($num);
            echo json_encode($head);
            echo "<br/><br/>";
            $mail = $mailbox->getMail($num, $markAsSeen);
            if ($headCondition !== null) {
                if (s($head->subject)->contains($headCondition)) {
                    array_push($arrMails, array('text' => $mail->textPlain, 'key' => $num));
                }
            } else {
                array_push($arrMails, array('text' => $mail->textPlain, 'key' => $num));
            }
        }
        return $arrMails;
    }

    public function getValuesByKeywords($arrMails, $keywords)
    {
        $arrValues = [];
        foreach ($arrMails as $mail) {
            array_push($arrValues, $this->breakKeysandValues($mail['text'], $keywords));
        }

        return $arrValues;
    }


    private function breakKeysandValues($mail, $keywords = [])
    {
        $data =  s($mail)->split("--Reply above this line--");
        $mailData = $data[0]->__toString();

        $arrIndexes = array();
        foreach ($keywords as $keyword) {
            $keyIndex = s($mailData)->indexOf($keyword);
            $keyLength = s($keyword)->length();
            $startIndex = ($keyIndex + $keyLength);
            $endIndex = $this->findNextKeyWordOccurence($mailData, $startIndex, $keywords);
            $result = s($mailData)->substr($startIndex, ($endIndex - $startIndex))->trim()->__toString();
            array_push(
                $arrIndexes,
                array(
                    "keyIndex" => $keyIndex,
                    'keyLength' => $keyLength,
                    'keyWord' => $keyword,
                    "startIndex" => $startIndex,
                    "endIndex" => $endIndex,
                    "result" => $result
                )
            );
        }

        return $arrIndexes;
        $arrIndexes = json_encode($arrIndexes);
        print_r($arrIndexes);
    }
    private function findNextKeyWordOccurence($mailData, $startIndex, $keywords)
    {
        // $mailData = s($mailData)->substr($startIndex);
        $maxIndex = null;
        $keyWordIndex = 0;
        foreach ($keywords as $keyword) {
            $keyWordIndex = s($mailData)->indexOf($keyword, $startIndex);
            if ($keyWordIndex != false) {
                if ($maxIndex == null || $keyWordIndex > $maxIndex) {
                    $maxIndex = $keyWordIndex;
                }
            }
        }

        if ($maxIndex == null) {
            $maxIndex = s($mailData)->indexOf(PHP_EOL, $startIndex);
        }

        return $maxIndex;
    }
}

class GmailClient extends MailClient
{
    public function connect()
    {
    }
}

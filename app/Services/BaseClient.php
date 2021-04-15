<?php

namespace App\Services;

use PhpImap as PhpImap;
use PhpImap\Mailbox as MailBox;
use function Stringy\create as s;
use Illuminate\Support\Facades\Log;

class BaseClient
{
    private $username;
    private $password;
    protected $readFolder;
    protected $fetchCondition;
    protected $imapAddress;
    protected $mailBox;
    protected $isConnected = false;
    protected $path =  null;

    private $splitChainsWith = "--Reply above this line--";

    public function __construct($imapAddress = null, $username = null, $password = null, $readFolder = "INBOX", $splitChainsWith = "--Reply above this line--", $path = null)
    {
        $this->imapAddress = $imapAddress;
        $this->username = $username;
        $this->password = $password;
        $this->readFolder = $readFolder;
        $this->splitChainsWith = $splitChainsWith;
        $this->path = $path;
    }
    public function connect()
    {
        $status = null;
        $this->isConnected = true;
        $this->disconnect();
        $this->mailBox = new Mailbox(
            $this->imapAddress, // IMAP server and mailbox folder
            $this->username, // Username for the before configured mailbox
            $this->password, // Password for the before configured username
            $this->path ? $this->path : null, // Directory, where attachments will be saved (optional)
            $serverEncoding = 'UTF-8' // Server encoding (optional)
        );
        $connection = [
            "status" => true,
            "message" => ""
        ];

        // Log::error($this->imapAddress);
        try {
            $status = $this->mailBox->statusMailbox();
            $connection['connection_status'] = $status;
        } catch (\Exception $ex) {
            $connection['status'] = false;
            $connection['message'] = $ex->getMessage();
        }

        return $connection;
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
    { }
    public function fetchWith($imapCondition, $headCondition = null)
    {
        $mailbox = $this->mailBox;
        $mailIds = [];

        try {
            // $mailbox->setServerEncoding('US-ASCII');
            retry_search: $mailIds = $mailbox->searchMailbox($imapCondition);
        } catch (\Exception $ex) {
            // Going down for Exchange servers such as Outlook
            // https://github.com/barbushin/php-imap/issues/101#issuecomment-378136507
            if (strpos($ex->getMessage(), " [BADCHARSET (US-ASCII)]")) {
                $oldEncoding = $mailbox->getServerEncoding();
                $mailbox->setServerEncoding('US-ASCII');
                $newEncoding = $mailbox->getServerEncoding();
                Log::error($oldEncoding . " -- " . $newEncoding);
                goto retry_search;
            } else {
                echo "IMAP connection failed: " . $ex;
                die();
            }
            Log::error("Failed in MailBox Search");
            // die();
        }
        $mailbox->disconnect();

        $arrMails = array();
        $markAsSeen = false;
        foreach ($mailIds as $num) {
            $head = $mailbox->getMailHeader($num);
            $headerInfo = $mailbox->getMailsInfo([$num]);
            $headerInfo = json_decode(json_encode($headerInfo[0]), true);
            $mail = $mailbox->getMail($num);

            $headerInfo['has_attachments'] = $mail->hasAttachments() ? true : false;
            $headerInfo['mailbox_attachments'] = null;
            if ($headerInfo['has_attachments']) {
                $headerInfo['mailbox_attachments'] = $mail->getAttachments();
            }

            if (isset($headerInfo['from'])) {
                $headerInfo['from_email'] = $this->getEmailFromText($headerInfo['from']);
            }
            if (isset($headerInfo['to'])) {
                $headerInfo['to_email'] = $this->getEmailFromText($headerInfo['to']);
            }
            // dd($headerInfo);
            // echo "<br/><br/>";
            $mail = $mailbox->getMail($num, $markAsSeen);
            $mailData = array_merge(array('text' => stripslashes($mail->textPlain), 'key' => $num), $headerInfo);
            if ($headCondition !== null) {
                if (s($head->subject)->contains($headCondition)) {
                    array_push($arrMails, $mailData);
                }
            } else {
                array_push($arrMails, $mailData);
            }
        }
        return $arrMails;
    }

    public function getEmailFromText($emailHead)
    {
        $firstIndex  = s($emailHead)->indexOfLast("<");
        $lastIndex  = s($emailHead)->indexOfLast(">");

        if ($firstIndex == false && $lastIndex == false) {
            return (s($emailHead)->indexOf("@") != false) ? s($emailHead)->trim()->__toString() : "";
        }
        return s($emailHead)->slice($firstIndex + 1, $lastIndex)->__toString();
    }

    public function findValuesByKeywords($mail, $keywords)
    {
        return $this->breakKeysandValues($mail['text'], $keywords);
    }


    private function breakKeysandValues($mail, $keywords = [])
    {
        // Break the Email from SPLIT string
        $data =  s($mail)->split($this->splitChainsWith);
        // Read the first part that has the most recent reply
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
                    "result" => $result,
                    "mail" => $mail
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
                    break;
                }
            }
        }
        if ($maxIndex == null) {
            // This is because the last question's answer can not be found in the above loop
            // We consider PHP_EOL as the end, and start it right after the question.
            // Most of the times, the next two characters of Question are PHP_EOL i.e \r\n :)
            // So we skip first carriage returns and start from new line to search
            if (s($mailData)->at($startIndex) == "\r" && s($mailData)->at($startIndex + 1) == "\n") {
                $startIndex += 2;
            }
            $maxIndex = s($mailData)->indexOf(PHP_EOL, $startIndex);
        }

        return $maxIndex;
    }
}

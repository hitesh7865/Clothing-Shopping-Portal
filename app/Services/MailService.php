<?php

namespace App\Services;

use App\User;
use App\Organization;
use App\Application;
use App\ApplicationActivity;
use App\Category;
use App\Question;
use App\Reminder;
use App\Setting;
use Carbon\Carbon;
use App\Mail\ApplicationReminder;

use App\Enums\ApplicationType;
use App\Enums\ApplicationStatus;
use File;
use Mail;
use DB;
use Crypt;
use function Stringy\create as s;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

// use App\Enums\APPLICATION_TYPE;

class MailService
{
    protected $userId;
    protected $orgId;
    protected $mailbox;
    protected $READ_DAYS;
    private $attachmentPath;
    private $attachmentDumpPath;
    public function __construct($userId = null, \App\Mailbox $mailbox = null, $duration = null)
    {
        if (!isset($duration) || $duration == null) {
            $duration = env("MAIL_READ_DAYS");
        }
        $this->userId = $userId;
        $organization = Organization::where('user_id', $userId)->first();
        $this->orgId = $organization->id;
        $this->mailbox = $mailbox;
        $this->READ_DAYS = $duration;

        $this->createPaths();
        $this->initConnection();
    }
    public function initConnection()
    {
        $mailbox = $this->mailbox;
        if (!$mailbox) {
            return;
        }
        $splitChainsWith = "--Reply above this line--";

        // Log::error($mailbox->toArray());
        $this->mailClient = MailClient::getClient(
            $mailbox->imap_host,
            $mailbox->imap_user,
            Crypt::decrypt($mailbox->imap_password),
            $mailbox->imap_folder,
            $splitChainsWith,
            $this->attachmentDumpPath
        );
    }
    public function createPaths()
    {
        // Created a custom disk named attachments in filesystems.php
        // We save all the files in /dump folder first, and save the actual attachments in the main directory
        $this->attachmentPath = Storage::disk('attachments')->getDriver()->getAdapter()->getPathPrefix() . $this->orgId . "/" . $this->userId;
        $this->attachmentDumpPath = Storage::disk('attachments_dump')->getDriver()->getAdapter()->getPathPrefix();

        if (!File::isDirectory($this->attachmentPath)) {
            File::makeDirectory($this->attachmentPath, 0770, true, true);
        }

        if (!File::isDirectory($this->attachmentDumpPath)) {
            File::makeDirectory($this->attachmentDumpPath, 0770, true, true);
        }
    }
    public static function testConnection($config)
    {
        $mailClient = MailClient::getClient(
            $config['imap_host'],
            $config['imap_user'],
            $config['imap_password']
        );
        return $mailClient->connect();
    }
    public function fetchEverything()
    {
        $this->fetchFreshAndSave();
        $this->sendQuestions();
        // $this->readRepliesAndSave();
        // $this->fetchQuestionedAndSave();
    }
    /*
    Not in use as we use forms to collect info
    */
    public function fetchQuestionedAndSave()
    {
        $rawEmails = $this->fetchWith(
            [
                'search_date' => date('d M Y', strtotime($this->READ_DAYS)),
                'freshness' => 'only_replied'
            ]
        );

        $filteredEmails  = $this->filterEmailsBySubjectAndAddKeys($rawEmails);

        // dd($filteredEmails);
        $this->addApplicationItems($filteredEmails);
        return $filteredEmails;
    }
    public function fetchFreshAndSave()
    {
        $rawEmails = $this->fetchWith(
            [
                'search_date' => date('d M Y', strtotime($this->READ_DAYS)),
                'freshness' => 'only_fresh'
            ]
        );

        // Append getAttachments
        foreach ($rawEmails as $key => $maildata) {
            $maildata['attachments'] = [];
            if ($maildata['has_attachments'] == true) {
                foreach ($maildata['mailbox_attachments'] as $attachment) {
                    if (File::exists($attachment->filePath)) {
                        $filedata =  pathinfo($attachment->filePath);
                        $fileUniqueName = $filedata['filename'] . (isset($filedata['extension']) ? ('.' . $filedata['extension']) : '');
                        $newFilePath = $this->attachmentPath . "/" . $fileUniqueName;
                        array_push($maildata['attachments'], [
                            'file_name' => $attachment->name,
                            'file_unique_name' => $fileUniqueName
                        ]);
                        copy($attachment->filePath, $newFilePath);
                    }
                }
            }
            $rawEmails[$key] = $maildata;
        }


        // Find the Category belonging to emails and push the related cat_id, deleted the ones that are not related
        $filteredEmails  = $this->filterEmailsByJobAndAddKeys($rawEmails);
        // Filter and remove the ones that do not match the filter criteria if any
        $filteredEmails = $this->filterEmailsByEmailAndCatId($filteredEmails);
        $this->addApplicationItems($filteredEmails);
        return $filteredEmails;
    }
    /*
    Reads email from database and parses it
    Not in use as we use forms to collect info
    */
    public function readRepliesAndSave()
    {
        $replies = $this->getUnReadReplies();
        $replies = $this->parseReplies($replies);
        $this->addReplies($replies);
        return $replies;
    }
    public function getUnReadReplies()
    {
        $unreadApplications = Application::where('org_id', $this->orgId)
            ->where('type', ApplicationType::REPLY)
            ->where('status', ApplicationStatus::PENDING_REVIEW)->get();
        return $unreadApplications->toArray();
    }
    public function parseReplies($emails)
    {
        $parsedReplies = [];

        foreach ($emails as $key => $mail) {
            // $questions = Question::where('cat_id', $mail['cat_id'])->get()->toArray();
            $questions  = Category::where('id', $mail['cat_id'])
                ->where('status', 1)
                ->first()->questions()->get()->toArray();
            $data = $this->mailClient->findValuesByKeywords($mail, array_pluck($questions, 'question'));
            $mail['raw_replies'] = $data;
            // $answers = array_pluck($data, 'result');
            $mail['replies'] = $this->getQuestionsByTextAndCatId($data, $mail['cat_id']);
            $emails[$key] = $mail;
            // array_push($parsedReplies, $data);
        }
        // return $parsedReplies;
        return $emails;
    }
    private function getQuestionsByTextAndCatId($questions, $catId)
    {
        // dd($questions);
        $arrQuestions = [];
        foreach ($questions as $key => $question) {
            $q = Question::where('cat_id', $catId)
                ->where('question', $question['keyWord'])
                ->where('status', 1)
                ->first();
            if ($q) {
                array_push($arrQuestions, ['question_id' => $q->id, 'question' => $question['keyWord'], 'answer' => $question['result']]);
            }
        }
        return $arrQuestions;
    }

    public function readReplies()
    { }

    public function sendQuestions($step = null)
    {
        // Get email address to send email who are fresh
        $freshApplications = $this->getFreshApplications();

        // Send emails
        $mailConfigs = [];
        foreach ($freshApplications as $application) {
            array_push($mailConfigs, $this->prepareQuestionEmail($application));
        }

        foreach ($mailConfigs as $mailConfig) {
            $this->sendEmail($mailConfig);
            Application::where('id', $mailConfig['app_id'])
                ->where('status', ApplicationStatus::PENDING_REVIEW)
                ->update(['status' => ApplicationStatus::QUESTIONED]);
        }

        // Update their status to Reviewing
    }
    public function sendReminders()
    {
        $orgsettings = Setting::where('org_id', $this->orgId)->first();
        if (!$orgsettings || $orgsettings->send_reminder_email_days == null || $orgsettings->send_reminder_email_days == 0 || empty($orgsettings->send_reminder_email_days)) {
            return null;
        }
        $applicants = $this->getApplicantsToRemind($orgsettings->send_reminder_email_days);
        // Send emails
        $mailConfigs = [];
        foreach ($applicants as $applicant) {
            array_push($mailConfigs, $this->prepareQuestionEmail($applicant, true));
        }

        foreach ($mailConfigs as $mailConfig) {
            $this->sendEmail($mailConfig);
            Reminder::create(['app_id' => $mailConfig['app_id']]);
        }
    }
    public function sendReminder($application)
    {
        $mailConfig = $this->prepareQuestionEmail($application, true);
        $this->sendEmail($mailConfig);
        Reminder::create(['app_id' => $mailConfig['app_id']]);
    }
    private function prepareQuestionEmail($application, $isReminder = false)
    {
        $link = route('response') . "?token=" . Crypt::encrypt($application->id);
        $config = [
            'to' => $application->from_email,
            'subject' => ($isReminder ? "Reminder : "  : "") . "YouRHired-" . $application->id . " : Re : " . $application->subject,
            'link' => $link,
            "baseurl" => "",
            "name" => $application->name,
            "reply_to" => $application->to_email,
            "app_id" => $application->id
        ];
        return $config;
    }
    public function sendEmail($config)
    {
        Mail::to($config['to'])->send((new ApplicationReminder($config)));
        return true;
        /*
        // Now using the mailable class above for brevity
        return Mail::send('emails.raise_question', $config, function ($message) use ($config) {
            $message->subject($config['subject']);
            $message->to($config['to']);
            $message->replyTo($config['reply_to']);

            $swiftMessage = $message->getSwiftMessage();
            $headers = $swiftMessage->getHeaders();
            if (isset($config['in_reply_to'])) {
                $headers->addTextHeader('In-Reply-To', $config['in_reply_to']);
                $headers->addTextHeader('References', $config['in_reply_to']);
            }
        });
        */
    }
    public function getApplicantsToRemind($days)
    {
        // Applicants that
        $mainQuery = DB::table('applications as leftTable')
            ->where('leftTable.status', ApplicationStatus::QUESTIONED)
            ->where('leftTable.type', ApplicationType::FRESH)
            ->where('leftTable.org_id', $this->orgId);


        $queryWithZeroReminders =   $mainQuery
            ->leftJoin('reminders', function ($query) {
                $query->on('leftTable.id', '=', 'reminders.id');
            })
            ->select('leftTable.*')
            ->whereNull('reminders.id')
            ->whereDate('leftTable.created_at', '<', Carbon::now()->subDays($days));


        return $queryWithZeroReminders->get();
    }
    public function getFreshApplications()
    {
        return Application::where('status', ApplicationStatus::PENDING_REVIEW)
            ->where('type', ApplicationType::FRESH)
            ->where('org_id', $this->orgId)
            ->get();
    }
    public function fetchWith($config)
    {
        $userId = "";

        if (!isset($config['user_id'])) {
            $userId = $this->userId;
        }

        if (isset($config['split_chains_with'])) {
            $splitChainsWith = $config['split_chains_with'];
        }

        $this->mailClient->connect();

        $mainCondition = "";
        if (isset($config['from_email'])) {
            $mainCondition = 'ALL SINCE "' . $config['search_date'] . '" FROM "' . $config['from_email'] . '"';
        } else {
            $mainCondition = 'ALL SINCE "' . $config['search_date'] . '"';
        }

        $arrMails = $this->mailClient->fetchWith($mainCondition, null);
        // dd($config);
        if (isset($config['freshness']) && $config['freshness'] == "only_fresh") {
            $arrMails = $this->filterFreshEmails($arrMails);
        } elseif (isset($config['freshness']) && $config['freshness'] == "only_replied") {
            $arrMails = $this->filterRepliedEmails($arrMails);
        }
        return $arrMails;
    }
    public function filterFreshEmails($arrMails)
    {
        foreach ($arrMails as $key => $mail) {
            if (isset($mail['references']) && isset($mail['in_reply_to'])) {
                unset($arrMails[$key]);
            }
        }
        return $arrMails;
    }
    public function filterRepliedEmails($arrMails)
    {
        foreach ($arrMails as $key => $mail) {
            if (!(isset($mail['references']) && isset($mail['in_reply_to']))) {
                unset($arrMails[$key]);
            }
        }
        return $arrMails;
    }
    public function filterEmailsByJobAndAddKeys($arrMails)
    {
        foreach ($arrMails as $key => $mail) {
            $cat_id = Category::getCategoryIdBySubjectAndOrgId($mail['subject'], $this->orgId);
            if ($cat_id != null) {
                $mail['cat_id'] = $cat_id;
                $arrMails[$key] = $mail;
            } else {
                unset($arrMails[$key]);
            }
        }
        return $arrMails;
    }
    public function filterEmailsByEmailAndCatId($arrMails)
    {
        foreach ($arrMails as $key => $mail) {
            $category = Category::where('id', $mail['cat_id'])->first();
            if ($category && !empty($category->email_filter) && $mail['from_email'] != $category->email_filter) {
                unset($arrMails[$key]);
            }
        }
        return $arrMails;
    }


    public function filterEmailsBySubjectAndAddKeys($arrMails)
    {
        // $test= Application::getAppIdBySubjectAndOrgId("Re: YOURHIRED-1223 Re : Mid Level Developer Candidate from Dog Digital", 1);
        // dd(Application::where('id', 100)->get());
        foreach ($arrMails as $key => $mail) {
            $appId = Application::getAppIdBySubjectAndOrgId($mail['subject']);
            if ($appId != null) {
                $application = Application::where('id', $appId)->first();
                if ($application) {
                    $mail['cat_id'] = $application->cat_id;
                    $mail['org_id'] = $application->org_id;
                    $mail['parent_id'] = $appId;
                    $mail['type'] = ApplicationType::REPLY;
                    $arrMails[$key] = $mail;
                } else {
                    unset($arrMails[$key]);
                }
            } else {
                unset($arrMails[$key]);
            }
        }
        return $arrMails;
    }

    public function addApplicationItems($arrMails)
    {
        return Application::addItemsByOrgId($arrMails, $this->orgId);
    }
    public function addReplies($replies)
    {
        return ApplicationActivity::addActivtyItems($replies);
    }
}

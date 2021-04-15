<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Organization;
use App\Application;
use App\ApplicationActivity;
use App\Category;
use App\Mailbox;
use Datatables;
use App\Services\MailService;
use DB;
use Carbon\Carbon;
use App\Enums\ApplicationStatus;
use App\Enums\ThirdPartySettings;
use App\Jobs\FetchJobs;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;

class ApplicationController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request)
    {
        // Storage::disk('attachments')->put('file.txt', 'Contents');
        $organization = Organization::where('user_id', Auth::user()->id)->first();
        $categories = Category::select("id", "title", "status")
            ->where("org_id", Auth::user()->organization_id)->get()->toArray();
        $categoryGroups = [
            "Active" => [],
            "Paused/Expired" => []
        ];

        // array_push($categoryGroups, ["id" => -1 , "title" => "All"]);
        foreach ($categories as $category) {
            if ($category['status']) {
                array_push($categoryGroups["Active"], $category);
            } else {
                array_push($categoryGroups["Paused/Expired"], $category);
            }
        }
        $statuses = ApplicationStatus::LIST_ALL;
        // array_unshift($categories, ["id" => -1 , "title" => "All"]);
        array_unshift($statuses, ["id" => -1, "value" => "All"]);


        $app_title = $request->input("title");
        $app_status = $request->input("status");
        $app_type = $request->input("type");
        $app_id = $request->input("id");
        return response()->view('application_list', [
            'categories' => $categories,
            'categoryGroups' => $categoryGroups,
            'statuses' => $statuses,
            'app_title' => $app_title,
            'app_status' => $app_status,
            'app_type' => $app_type,
            'app_id' => $app_id
        ]);
    }
    public function getAppActivityByAppId(Request $request, $id)
    {
        $organization = Organization::where('user_id', Auth::user()->id)->first();

        $appActivities = ApplicationActivity::where('app_id', $id)
            ->join("questions", 'questions.id', '=', 'application_activities.question_id')
            ->where('questions.status', 1)
            ->select(['application_activities.app_id', 'application_activities.answer', 'questions.question'])->get();

            
        $application = Application::where('id', $id)->first();
        // echo '<pre>';
        //     print_r($application);die;
        return response()->json(['questions' => $appActivities, 'text' => $application->text]);
    }
    public function getAttachment(Application $application, $fileUniqueName, Request $request)
    {
        $organization = Organization::where('user_id', Auth::user()->id)->first();
        $pathToFile = Storage::disk('attachments')->getDriver()->getAdapter()->getPathPrefix() . Auth::user()->organization_id . "/" . Auth::user()->id . "/" . $fileUniqueName;
        return response()->download($pathToFile);
    }
    public function getApplications(Request $request)
    {
        $appTitleId = ($request->input("app_title") == null || $request->input("app_title") == "-1") ? "%" : $request->input("app_title");
        $appStatusId = ($request->input("app_status") == null || $request->input("app_status") == "-1")  ? "%" : $request->input("app_status");
        $appType = ($request->input("app_type") == null || $request->input("app_type") == "-1")  ? "%" : $request->input("app_type");
        $appId = ($request->input("app_id") == null || $request->input("app_id") == "-1")  ? "%" : $request->input("app_id");
        $organization = Organization::where('user_id', Auth::user()->id)->first();
        $organizations = Application::where('org_id', Auth::user()->organization_id);

        $mainQuery = DB::table('applications as leftTable')
            ->select('leftTable.*', 'rightTable.id as rightID', 'categories.id as CatId', 'categories.title as title')

            ->leftJoin('applications as rightTable', function ($query) {
                $query->on('leftTable.id', '=', 'rightTable.parent_id');
            })
            ->whereNull('rightTable.id')
            ->where('leftTable.org_id', Auth::user()->organization_id)
            ->where('leftTable.cat_id', 'like', $appTitleId)
            ->where('leftTable.status', 'like', $appStatusId)
            ->where('leftTable.type', 'like', $appType)
            ->where('leftTable.id', 'like', $appId)
            ->orderBy('leftTable.created_at', 'desc');


        $queryWithZeroReminders =   $mainQuery
            ->join('categories', function ($query) {
                $query->on('leftTable.cat_id', '=', 'categories.id');
            });
        // ->orderBy('leftTable.updated_at', 'desc');

        // dd($queryWithZeroReminders->get()->toArray());

        // DB::table("applications as app")
        //     ->select("app.*, categories.id as CatId")
        // return Carbon::now()->diffInDays(Carbon::now()->subYear());

        return DataTables::of($mainQuery)
            ->addColumn('current_status', 'yajra.category_action')
            ->addColumn('rating_tpl', 'yajra.application_rating')
            ->addColumn('actions', 'yajra.application_action')
            ->addColumn('updated_at', function ($record) {
                $tpl = "";
                $dt = new Carbon($record->updated_at);
                $diff = Carbon::now()->diffInDays($dt);
                // Carbon::createFromTimestamp(-);
                $tplClass = "category__time_today";
                if ($diff <= 1) {
                    $diffInHrs = Carbon::now()->diffInHours($dt);
                    if ($diffInHrs > 8) {
                        $tpl = "Applied today";
                    } elseif ($diffInHrs < 1) {
                        $tpl = "" . Carbon::now()->diffInMinutes($dt) . "mins ago";
                    } else {
                        $tpl = "" . Carbon::now()->diffInHours($dt) . "hrs ago";
                    }
                } elseif ($diff < 7) {
                    $tpl = "" . Carbon::now()->diffInDays($dt) . "days ago";
                    $tplClass = "category__time_week";
                } elseif ($diff > 7 && $diff < 30) {
                    $tpl = "Over a week ago";
                    $tplClass = "category__time_month";
                } else {
                    $tpl = "Over a month ago";
                    $tplClass = "category__time_month-over";
                }
                return "<div class='category__time " . $tplClass .  "'><span class='glyphicon glyphicon-time' aria-hidden='true'></span><span class='category__time-txt'> " . $tpl . "</span></div> ";
            })
            ->escapeColumns([])
            ->setRowId('id')
            ->make(true);
    }
    public function sendReminder(Request $request)
    {
        $appId = $request->input("app_id");
        $application = Application::where("id", $appId)->first();
        $mailService = new MailService(Auth::user()->id, null);
        $mailService->sendReminder($application);
        return response()->json(["status" => true]);
    }
    public function fetchAll()
    {
        $response = [
            "status" => true,
            "message" => "Fetching jobs in the background"
        ];

        $job = (new FetchJobs(Auth::user()->id))->onQueue(env('QUEUE_IMMEDIATE'));
        dispatch($job);


        // $this->fetchFresh();
        // $this->raiseQuestions();
        // $this->readReplies();
        // $this->fetchQuestioned();
        return response()->json($response);
    }
    public function raiseQuestions()
    {
        $mailService = new MailService(Auth::user()->id, Mailbox::where('id', Auth::user()->id)->first());
        $mailService->sendQuestions();
    }

    public function fetchFresh()
    {
        $mailService = new MailService(Auth::user()->id, Mailbox::where('id', Auth::user()->id)->first(), env("MAIL_FETCH_NOW_DURATION"));
        $mailService->fetchFreshAndSave();
    }

    public function fetchQuestioned()
    {
        $mailService = new MailService(Auth::user()->id, Mailbox::where('id', Auth::user()->id)->first());
        $mailService->fetchQuestionedAndSave();
    }

    public function readReplies()
    {
        $mailService = new MailService(Auth::user()->id, Mailbox::where('id', Auth::user()->id)->first());
        $mailService->readRepliesAndSave();
    }
    public function sendReminders()
    {
        $organization = Organization::where('user_id', Auth::user()->id)->first();
        $mailboxes = Mailbox::where('org_id', Auth::user()->organization_id)
            ->where('status', 1)->get();
        foreach ($mailboxes as $mailbox) {
            (new MailService(Auth::user()->id, $mailbox))->sendReminders();
        }
    }

    public function generateRating()
    {
        $application = Application::where("id", 4)->first();
        if ($application->status == ApplicationStatus::RESPONDED) {
            $application->generateRating();
        }
    }
    public function update(Request $request)
    {
        if ($request->ajax()) {
            if (!empty($request->input('subject'))) {
                $config = $request->all();
                $application = Application::where('id', $request->input('id'))->first();
                $config['from_email'] = $application->from_email;
                Mail::send('emails.hire_confirmation', $config, function ($message) use ($config) {
                    $message->subject($config['subject']);
                    $message->to($config['from_email']);

                    $swiftMessage = $message->getSwiftMessage();
                    $headers = $swiftMessage->getHeaders();
                    if (isset($config['in_reply_to'])) {
                        $headers->addTextHeader('In-Reply-To', $config['in_reply_to']);
                        $headers->addTextHeader('References', $config['in_reply_to']);
                    }
                });
            }

            if (!empty($request->input('status'))) {
                Application::where('id', $request->input('id'))
                    ->update(['status' => $request->input('status')]);
            }

            if (!empty($request->input('rating')) || !empty($request->input('name')) && empty($request->input('status'))) {
                $application = Application::where('id', $request->input('id'))->first();
                $application->update($request->all());
            }
            return response()->json(['status' => true]);
        } else {
            abort("403");
        }
    }

    public function checkCookie(Request $request) {
        $cookieName = $request->input("cookieName");//carbonateAuth
        $cookieValue = $request->cookie($cookieName);
        $response['valid'] = 0;
        if($cookieValue) {
            //cookie exists
            if($cookieName == "carbonateAuth") {
                //check for token verification
                $carbonateValidator = $this->verifyCarbonateAccount($cookieValue);

                if($carbonateValidator['valid']) {
                    $response['valid'] = $carbonateValidator['valid'];
                    $response['carbonateUser'] = $carbonateValidator['userName'];
                    $response['carbonateUserEmail'] = $carbonateValidator['email'];
                } else {
                    $this->deleteCarbonateCookie();
                }
            }
        } else {
            //cookie not set
        }
        return response()->json($response);
    }

    public function deleteCarbonateCookie() {
        \Cookie::queue(\Cookie::forget('carbonateAuth'));
        return;
    }

    public function carbonateAuthentication(Request $request) {
        $email = $request->input("email");
        $password = $request->input("password");
        $responseData['valid'] = 0;
        if($email && $password) {
            $carbonateDetails = array('api_key' => ThirdPartySettings::carbonateApiKey, 'email' => $email, 'password' => $password, 'service_name' => 'YouRHired');
            $payload = urlencode(json_encode($carbonateDetails));
            $carbonateValidator = $this->verifyCarbonateAccount($payload);
            if($carbonateValidator['valid']) {
                $cookieResponse = new Response($carbonateValidator);
                $responseData = json_encode($carbonateValidator);
                $cookieName = 'carbonateAuth';
                $cookieTimeout = 1440; // minutes -> 24hr
                return $cookieResponse->withCookie(cookie($cookieName, $responseData, $cookieTimeout));
            }
        }
        return response()->json($responseData);
    }

    public function verifyCarbonateAccount($carbonateDetails) {
        $carbonateValidator['valid'] = false;
        $baseUrl = ThirdPartySettings::carbonateBaseUrl;
        $url = $baseUrl . '/carbonate-validation';
        
        // Prepare new cURL resource
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $carbonateDetails);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($carbonateDetails))
        );
        $result = curl_exec($ch);
        curl_close($ch);

        if($result) {
            $decodedValues = json_decode($result, true);
            if($decodedValues['status'] == 1) {
                $carbonateValidator['valid'] = 1;
                $carbonateValidator['userName'] = $decodedValues['data']['user_name'];
                $carbonateValidator['email'] = $decodedValues['data']['email'];
                $carbonateValidator['secret_key'] = $decodedValues['data']['secret_key'];
                $carbonateValidator['user_token'] = $decodedValues['data']['token'];
                $carbonateValidator['api_key'] = ThirdPartySettings::carbonateApiKey;
            }
        }

        return $carbonateValidator;
    }

    public function carbonateApplicantSync(Request $request) {
        $applicationId = $request->input("applicant_id");
        $cookieValue = $request->cookie("carbonateAuth");
        $response['status'] = 0;
        $response['error_code'] = 400;
        $response['message'] = 'Bad request!';
        if($applicationId) {
            if($cookieValue) {
                $applicationData = Application::where('id', $applicationId)->first();
                if($applicationData) {
                    $userEmail = $applicationData['from_email'];
                    $userName = explode(' ', $applicationData['name']);
                    $userRole = 3; //<= Staff
                    $first_name = array_shift($userName);
                    $last_name = implode(' ', $userName);

                    $cookieDetails = json_decode($cookieValue, true);
                    $secret_key = $cookieDetails['secret_key'];
                    $token = $cookieDetails['user_token'];
                    $authorEmail = $cookieDetails['email'];
                    if($secret_key && $token) {
                        $req['api_key'] = ThirdPartySettings::carbonateApiKey;;
                        $req['secret_key'] = $secret_key;
                        $req['token'] = $token;
                        $req['author_email'] = $authorEmail;
                        $req['users'] = array(array('role' => 'Staff', 'email' => $userEmail, 'first_name' => $first_name, 'last_name' => $last_name));

                        $payload = urlencode(json_encode($req));
                        $baseUrl = ThirdPartySettings::carbonateBaseUrl;
                        $url = $baseUrl . '/thirdparty/add/users';
                        
                        $ch = curl_init($url);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
                        curl_setopt($ch, CURLOPT_POST, true);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                            'Content-Type: application/json',
                            'Content-Length: ' . strlen($payload))
                        );
                        $result = curl_exec($ch);
                        curl_close($ch);

                        if($result) {
                            $decodedValues = json_decode($result, true);
                            $response['status'] = $decodedValues['status'];
                            $response['error_code'] = $decodedValues['error'];
                            $response['message'] = $decodedValues['data']['message'];
                            if($response['status'] == 1) {
                                $errorCount = intval($decodedValues['data']['count_error']);
                                $successCount = intval($decodedValues['data']['count_success']);
                                if($successCount == 0) {
                                    $response['message'] = 'User not added';
                                    $response['status'] = 0;
                                } else {
                                    Application::where('id', $applicationId)->update(array('carbonate_synced' => '1'));
                                }
                                if($errorCount > 0) {
                                    $response['message'] .= implode(', ', $decodedValues['data']['errors']);
                                }
                            }
                        } else {
                            $response['error_code'] = 204;
                            $response['message'] = "User could not be added to Carbonate!";
                        }
                    } else {
                        $response['error_code'] = 401;
                        $response['message'] = "Token expired";
                    }
                } else {
                    $response['error_code'] = 404;
                    $response['message'] = 'Error fetching applicant details!';
                }
            } else {
                $response['error_code'] = 403;
                $response['message'] = 'Carbonate auth not found!';
            }
            
        }
        return response()->json($response);
    }

}

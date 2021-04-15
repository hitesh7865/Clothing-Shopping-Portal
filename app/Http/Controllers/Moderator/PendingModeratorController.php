<?php

namespace App\Http\Controllers\Moderator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Category;
use App\Organization;
use Illuminate\Support\Facades\Auth;
use App\Currency;
use Datatables;
use App\Mailbox;
use App\JobCategory;
use App\JobTag;
use App\Question;
use App\QuestionSet;
use App\Tag;
use Mail;
use App\User;
use App\job_reject_logs;
use App\Mail\JobApprove;
use App\Mail\JobReject;

class PendingModeratorController extends Controller
{
    public $status;
    public function __construct()
    {
        $this->middleware('auth');
        $this->status = config('enums.JOB_STATUS');
    }

    public function index()
    {
        return view('moderator.pending_moderator.index');
    }

    public function getJobs()
    {
        $organization = Organization::where('user_id', Auth::user()->id)->first();
        $jobs = Category::where([
            ['org_id', '=', Auth::user()->organization_id],
            ['status', '=', 3],

        ])->orderBy('updated_at', 'desc');
        return DataTables::of($jobs)
            ->addColumn('action', function ($job) {
                return '<a href="/pending-moderator/' . $job->id . '/edit' . '" class="btn btn_xs btn_blue">Review</a>
                        
                ';
            })->addColumn('mailboxes', function (Category $job) {
                $html = "";
                if ($job->mailboxes()) {
                    $mailBoxIds = $job->mailboxes()->get()->toArray();
                    $mailBoxIds = array_pluck($mailBoxIds, 'id');
                    $mailboxes = Mailbox::whereIn('id', $mailBoxIds)->get(['imap_name', 'id']);
                    foreach ($mailboxes as $mailbox) {
                        $html = $html . "<div class='status status_mailbox status_hover' id='mailbox_" . $mailbox->id . "'>" . $mailbox->imap_name . '</div>';
                    }
                }

                return $html;
            })
            ->addColumn('actions', 'yajra.category_action')
            ->rawColumns(['action', 'mailboxes', 'actions'])
            ->make(true);
    }

    public function edit(Category $category)
    {

        // echo "<pre>";
        // print_r($category);
        // die;

        $currencies = Currency::where('status', '1')->get();

        $organization = Organization::where('user_id', Auth::user()->id)->first();
        $job = $category;

        $allMailboxes = Mailbox::where('org_id', Auth::user()->organization_id)
            ->where('status', 1)
            ->orderBy('imap_name', 'asc')
            ->get()->toArray();

        $mailboxes = $job->mailboxes()->get()->toArray();

        // Assign selected
        foreach ($allMailboxes as $key =>   $allMailbox) {
            foreach ($mailboxes as $mailbox) {
                if ($allMailbox['id'] == $mailbox['id']) {
                    $allMailbox['selected'] = true;
                    $allMailboxes[$key] = $allMailbox;
                }
            }
        }

        $job_categories = JobCategory::where('org_id', Auth::user()->organization_id)->where('status', 1)->orderBy('name', 'asc')->get()->toArray();
        $question_sets = QuestionSet::where('org_id', Auth::user()->organization_id)->where('status', 1)->orderBy('name', 'asc')->get()->toArray();
        $tags = Tag::where('org_id', Auth::user()->organization_id)->where('status', 1)->orderBy('name', 'asc')->get()->toArray();
        $job_tags = JobTag::where('job_id', '=', $job['id'])
            ->where('status', '=', '1')->pluck('tag_id')->toArray();

        // Get Questions
        $allQuestions = Question::where('status', 1)
            ->where('org_id', Auth::user()->organization_id)->get()->toArray();
        $relatedQuestions = $job->questions()->get(['category_question.question_id as question_id'])->toArray(); //->get(['category_question.id','question','status'])->toArray();
        
        //Get Job reject reason
        $rejectdata = array();
        $rejectdataa = job_reject_logs::where('job_id',$job->id)->where('status', 1)->get()->toArray();

        // echo "<pre>";
        // print_r($rejectdata);die;
        //Get Job reject User_name
        foreach($rejectdataa as $key => $rejected_by){
            $rejectdata[$key]['reason'] = $rejected_by['reason'];
            $rejectdata[$key]['name'] = User::where('id',$rejected_by['rejected_by'])->select('fullname')->value('fullname');
            $rejectdata[$key]['rejected_by'] = User::where('id',$rejected_by['rejected_by'])->select('user_name')->value('user_name');
            $rejectdata[$key]['created_at'] = $rejected_by['created_at'];
        }

        //
   
        
        foreach ($allQuestions as $key => $question) {
            foreach ($relatedQuestions as $innerKey => $relatedQuestion) {
                if ($question['id'] == $relatedQuestion['question_id']) {
                    $question['selected'] = true;
                    $allQuestions[$key] = $question;
                }
            }
        }

        
        
        if ($allMailboxes) {
            return response()->view('moderator.pending_moderator.edit', ['data' => $job, 'currencies' => $currencies, 'mailboxes' => $allMailboxes, 'questions' => $allQuestions, 'job_categories' => $job_categories, 'question_sets' => $question_sets, 'tags' => $tags, 'job_tags' => $job_tags, 'rejectdata' => $rejectdata]);
        } else {
            return response()->view('moderator.pending_moderator.edit', ['data' => $job ,'currencies' => $currencies, 'mailboxes' => '', 'questions' => $allQuestions, 'job_categories' => $job_categories, 'question_sets' => $question_sets, 'tags' => $tags, 'job_tags' => $job_tags, 'rejectdata' => $rejectdata]);
        }
    }

    public function update($id, Request $request)
    {
        //die('222');

        // echo $id;
        // die;

        // echo "<pre>";
        // print_r($request->approve);
        // die;

        $job = Category::where('id', $id)->first();

        $user = User::select('fullname', 'email')->where('id', $job->created_by)->first();
        // echo "<pre>";
        // print_r($user);
        // die;

        $data = [
            'job_name' => $job->title,
            'user_name' => $user->fullname,
        ];

        // if () {

        // }

        if ($request->approve == "true") {

            $job = Category::where([
                ['org_id', '=', Auth::user()->organization_id],
                ['id', '=', $id],
            ])->update(['status' => 1]);

            if ($job) {
                $data['job_status'] = "Approved";
                Mail::to($user->email)->send((new JobApprove('Job Approved', $data)));

                $request->session()->flash('success', 'Job is Active Successfully');
                return redirect(route('pending-moderator.index'));
            } else {
                $request->session()->flash('success', 'there is Some Problem !!');
                return redirect(route('pending-moderator'));
            }
        } else {


            $job = Category::where([
                ['org_id', '=', Auth::user()->organization_id],
                ['id', '=', $id],
            ])->update(['status' => $this->status['REJECT']]);

            if ($job) {
                $data['job_status'] = "Reject";

                $requestData = $request->all();
                $requestData['reason'] = $request->reason;
                $requestData['rejected_by'] = Auth::user()->id;
                $requestData['status'] = 1;
                $requestData['job_id'] = $id;

                $job_rejected_log = job_reject_logs::create($requestData);

                Mail::to($user->email)->send((new JobReject('Job Reject')));

                $request->session()->flash('success', 'Job Is Rejected Successfully');
                return redirect(route('pending-moderator.index'));
            } else {
                $request->session()->flash('success', 'there is Some Problem !!');
                return redirect(route('pending-moderator.index'));
            }
        }
    }
}

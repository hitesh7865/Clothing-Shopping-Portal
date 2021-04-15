<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Organization;
use App\Category;
use App\JobCategory;
use App\FunctionalArea;
use App\JobTag;
use App\job_reject_logs;
use App\Mailbox;
use App\Question;
use App\QuestionSet;
use Carbon\Carbon;
use App\Tag;
use App\JobLocation;
use App\JobPreferredLocation;
use App\Currency;
use Mail;
use App\Mail\JobCreate;
use Bugsnag;

use Datatables;

class CategoryController extends Controller
{

    public $status;
    public function __construct()
    {
        $this->middleware('auth');
        $this->status = config('enums.JOB_STATUS');

    }

    /*
    Show liting
    */
    public function index()
    {
       
        return view('category_list');

    }
    public function getJobs()
    {
        $role = config('enums.ROLE');
        $organization = Organization::where('user_id', Auth::user()->id)->first();

        if(Auth::user()->role_id == $role['JOB_POSTER'])
        {
            
            $jobs = Category::where([
                ['org_id','=',Auth::user()->organization_id ],
                ['created_by', '=' ,Auth::user()->id],
                ['status','!=','5']

                ])
                ->orderBy('updated_at', 'desc');
        }
        else if(Auth::user()->role_id == $role['JOB_POSTER'] || Auth::user()->role_id == $role['MODERATOR']){
          
            $jobs = Category::where([
                [ 'org_id', '=' ,Auth::user()->organization_id ],
                ['created_by', '=' ,Auth::user()->id],
                ['status','!=','5']
                ])
                ->orderBy('updated_at', 'desc');
        }
        else{
           
        $jobs = Category::where('org_id', Auth::user()->organization_id)
                        ->whereNotIn('status', [5])
                        ->orderBy('updated_at', 'desc');
        }
        return DataTables::of($jobs)
            ->addColumn('action', function ($jobs) {
                return '<a href="/jobs/' . $jobs->id . '/edit' . '" class="btn btn_xs btn_blue">Edit</a> &nbsp;
                <form id="delete-category'.$jobs->id.'" action="'.route('jobs.destroy',$jobs->id) .'" method="POST" class="">
                <input type="hidden" name="_token" value="'.csrf_token().'">
                <input type="hidden" name="_method" value="delete" />
                <button type="submit" class="btn btn_xs btn_red">Delete</button></form>';
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

    /*
    Add/Create new
    */
    public function create()
    {

        $currencies = Currency::where('status', '1')->get();
        
        $organization = Organization::where('user_id', Auth::user()->id)->first();

        // Send all the mailboxes to select from

        $mailboxes = Mailbox::where('org_id', Auth::user()->organization_id)->where('status', 1)->orderBy('imap_name', 'asc')->get()->toArray();
        $job_categories = JobCategory::where('org_id', Auth::user()->organization_id)->where('status', 1)->orderBy('name', 'asc')->get()->toArray();
        $question_sets = QuestionSet::where('org_id', Auth::user()->organization_id)->where('status', 1)->orderBy('name', 'asc')->get()->toArray();
        $functional_area = FunctionalArea::where('org_id', Auth::user()->organization_id)->where('status', 1)->orderBy('name','asc')->get()->toArray();
        $tags = Tag::where('org_id', Auth::user()->organization_id)->where('status', 1)->orderBy('name', 'asc')->get()->toArray();
        $users = User::where([
            ['status' , '=' , 1],
            ['organization_id' , '=' , Auth::user()->organization_id]
            ])->orderBy('fullname', 'asc')->get()->toArray();

        foreach ($mailboxes as $key =>   $mailbox) {
            $mailbox['selected'] = true;
            $mailboxes[$key] = $mailbox;
        }
        $questions = Question::where('status', 1)->where('org_id', Auth::user()->organization_id)->get();
        
        return response()->view('category', ['users'=> $users,'mailboxes' => $mailboxes, 'questions' => $questions,'job_categories' => $job_categories,'question_sets' => $question_sets,'tags' => $tags,'currencies' => $currencies,'functional_area' => $functional_area]);
    }
    /*
    Edit a Job/Category
    */
    public function edit(Category $category)
    {
        $currencies = Currency::where('status', '1')->get();


        $citylist = array();
        $organization = Organization::where('user_id', Auth::user()->id)->first();
        $job = $category;
        $allMailboxes = Mailbox::where('org_id', Auth::user()->organization_id)
            ->where('status', 1)
            ->orderBy('imap_name', 'asc')
            ->get()->toArray();
        $mailboxes = $job->mailboxes()->get()->toArray();
        $users = User::where('status', 1)->where('organization_id', Auth::user()->organization_id)->orderBy('fullname', 'asc')->get()->toArray();

        // Assign selected
        foreach ($allMailboxes as $key =>   $allMailbox) {
            foreach ($mailboxes as $mailbox) {
                if ($allMailbox['id'] == $mailbox['id']) {
                    $allMailbox['selected'] = true;
                    $allMailboxes[$key] = $allMailbox;
                }
            }
        }
        $functional_area = FunctionalArea::where('org_id', Auth::user()->organization_id)->where('status', 1)->orderBy('name','asc')->get()->toArray();
        $job_categories = JobCategory::where('org_id', Auth::user()->organization_id)->where('status', 1)->orderBy('name', 'asc')->get()->toArray();
        $question_sets = QuestionSet::where('org_id', Auth::user()->organization_id)->where('status', 1)->orderBy('name', 'asc')->get()->toArray();
        $tags = Tag::where('org_id', Auth::user()->organization_id)->where('status', 1)->orderBy('name', 'asc')->get()->toArray();
        $job_tags = JobTag::where('job_id','=',$job['id'])
        ->where('status','=','1')->pluck('tag_id')->toArray();
        $job_locations = JobLocation::where('job_id','=',$job['id'])->where('status', 1)->orderBy('city', 'asc')->get()->toArray();
        $job_preferred_locations = JobPreferredLocation::where('job_id','=',$job['id'])->where('status', 1)->orderBy('city_name', 'asc')->get()->toArray();
        // dd($job_locations);
        foreach ($job_preferred_locations as $key => $value) {            
            array_push($citylist,
                [
                'city_id' => $value['city_id'],
                'city_name' => $value['city_name']
            ]);
        }

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

        //dd($citylist);
        // Get Questions
        $allQuestions = Question::where('status', 1)
            ->where('org_id', Auth::user()->organization_id)->get()->toArray();
        $relatedQuestions = $job->questions()->get(['category_question.question_id as question_id'])->toArray(); //->get(['category_question.id','question','status'])->toArray();

        foreach ($allQuestions as $key => $question) {
            foreach ($relatedQuestions as $innerKey => $relatedQuestion) {
                if ($question['id'] == $relatedQuestion['question_id']) {
                    $question['selected'] = true;
                    $allQuestions[$key] = $question;
                }
            }
        }
    //    echo '<pre>';
    //    print_r($job);
    //    die;
        if ($allMailboxes) {
            return response()->view('category.edit', ['users'=> $users, 'data' => $job, 'mailboxes' => $allMailboxes, 'questions' => $allQuestions,'job_categories' => $job_categories,'question_sets' => $question_sets,'tags' => $tags,'job_tags' => $job_tags, 'job_locations' => $job_locations, 'citylist' => $citylist, 'job_preferred_locations'=>$job_preferred_locations,'currencies' => $currencies,'functional_area' => $functional_area, 'rejectdata' => $rejectdata]);
        } else {
            return response()->view('category.edit', ['users'=> $users, 'data' => $job, 'mailboxes' => [], 'questions' => $allQuestions,'job_categories' => $job_categories,'question_sets' => $question_sets,'tags' => $tags,'job_tags' => $job_tags,'job_locations' => $job_locations, 'citylist' => $citylist, 'job_preferred_locations'=>$job_preferred_locations,'currencies' => $currencies,'functional_area' => $functional_area, 'rejectdata' => $rejectdata]);
        }
    }

    public function store(Request $request)
    {   
       
        $role = config('enums.ROLE');
        $organization = Organization::where('user_id', Auth::user()->id)->first();
        $requestData = $request->all(); 
        
        
        
        if(Auth::user()->id !== 1){
            $requestData['created_by'] = Auth::user()->id;
            $create_job_by = Auth::user()->id; 
        }
        else{
            $create_job_by = $requestData['created_by'];
        }
        //$requestData['created_by'] = Auth::user()->id; dd($requestData['created_by']);        
        $requestData['org_id'] = Auth::user()->organization_id;
        
        

        if(Auth::user()->role_id == $role['MODERATOR'] || Auth::user()->role_id == 1){
            $requestData['status'] = (isset($requestData['status']) && $requestData['status'] == "on") ? 1 : 0;
        } else {
            $requestData['status'] = '3';
        }
       
        
        $category = Category::create($requestData);
        $mailIds = $request['mailboxes'];
        if (isset($mailIds) && $mailIds !== '') {
            $category->mailboxes()->attach($mailIds);
        }     

        // Save the questions

        $questions = $request['questions'];
        $category->questions()->sync($questions);

        if($request->tags) {
            foreach ($request->tags as $tag) {
                $job_id = $category->id;
                JobTag::create([
                    'job_id' => $job_id,
                    'tag_id' => $tag,
                ]);
            }
        }        
        
        // Save the questions
        // $questions = $request['questions'];
        // if (count($questions) > 0) {
        //     foreach ($questions as $question) {
        //         Question::create(['question' => $question, 'cat_id' => $category->id ]);
        //     }
        // }
        
        // START Location store

        if ($category) {
            if(isset($request['city'])){    
                foreach ($request['city'] as $city) {                    
                    //$city = json_encode($city); 
                    $job_id = $category->id;                    
                    $result = JobLocation::create([
                    'job_id' => $job_id,
                    'city' => (isset($city['name']) && !empty($city['name'])) ? $city['name'] : "" ,
                    'city_id' => $city['id'],  //(isset($city['id']) && !empty($city['id'])) ? $city['id'] : "" ,
                    'country_code' => $request->location,
                    'country' => $request->country_name
                ]);
                }   
            }
        }

        // END Location 
        
        // START Preferred Location store 

        if ($category) {
            if (isset($request['PreferredCity'])) {
                foreach ($request['PreferredCity'] as $city) {                    
                    $job_id = $category->id;
                    JobPreferredLocation::create([
                        'job_id' => $job_id,
                        'city_id' => (isset($city['id']) && !empty($city['id'])) ? $city['id'] : "" ,
                        'city_name' => (isset($city['name']) && !empty($city['name'])) ? $city['name'] : "" ,
                        'country' => $request->PreferredCountry
                    ]);
                }
            }
        }

        // END Preferred Location  

        $response['success'] = 'Details have been saved! Chillax.';

        return $this->sendApiResponse($response);
    }
    public function update(Category $category, Request $request)
    {   
        $requestData = $request->all();
        $role = config('enums.ROLE');
        $organization = Organization::where('user_id', Auth::user()->id)->first();
        // if($requestData['hidden_status_code'] == '0' || $requestData['hidden_status_code'] == '1' || $requestData['hidden_status_code'] == '2') {
        //     $requestData['status'] = (isset($requestData['status']) && $requestData['status'] == "on") ? 1 : 0;
        // }

        if(Auth::user()->role_id == $role['MODERATOR'] || Auth::user()->role_id == 1){
            $requestData['status'] = (isset($requestData['status']) && $requestData['status'] == "on") ? 1 : 0;
        } else {
            $requestData['status'] = '3';
        }

        $category->update($requestData);

        // Mailboxes
        $mailIds = $request['mailboxes'];
        $category->mailboxes()->sync($mailIds);

        // Save Questions
        if (!isset($requestData['questions'])) {
            $requestData['questions'] = [];
        }
        $category->questions()->sync($requestData['questions']);

        JobTag::where('job_id', '=', $category->id)->update(['status' => 2]);
        if($request->tags) {
            foreach ($request->tags as $tag) {
                $job_id = $category->id;
                JobTag::create([
                    'job_id' => $job_id,
                    'tag_id' => $tag,
                ]);
            }
        }

        // START Location update
        
        if ($category) {
            if(isset($request['city']) && isset($category->id)){    
                $deletedCity = JobLocation::where('job_id',$category->id)->delete();
                foreach ($request['city'] as $city) {
                    $job_id = $category->id;                    
                    $result = JobLocation::create([
                    'job_id' => $job_id,
                    'city' => (isset($city['name']) && !empty($city['name'])) ? $city['name'] : "" ,
                    'city_id' => $city['id'],  //(isset($city['id']) && !empty($city['id'])) ? $city['id'] : "" ,
                    'country_code' => $request->location,
                    'country' => $request->country_name,
                ]);
                }
            }
        }

        // END Location update
        // dd($request['PreferredCity']);
        // START JobPreferredLocation Location update
        
        if ($category) {
            if(isset($request['PreferredCity']) && isset($category->id)){    
                $deletedCity = JobPreferredLocation::where('job_id',$category->id)->delete();
                foreach ($request['PreferredCity'] as $city) {
                    $job_id = $category->id;                    
                    $result = JobPreferredLocation::create([
                    'job_id' => $job_id,
                    'city_name' => (isset($city['name']) && !empty($city['name'])) ? $city['name'] : "" ,
                    'city_id' => $city['id'],  //(isset($city['id']) && !empty($city['id'])) ? $city['id'] : "" ,
                    'country' => $request->PreferredCountry
                ]);
                }
            }
        }

        // END JobPreferredLocation Location update

        
        /*
        // Questions, find the different between new tags created and update/delete of old questions
        /// [1,2,3,4,5]  [1,2,'hello world']
        $oldQuestions = $category->questions()->get(['id'])->toArray();
        $oldQuestions = array_pluck($oldQuestions, 'id');

        $questions = empty($request['questions']) ? [] : $request['questions'];
        $questionsToDelete = [];
        $questionsToAdd = [];
        // print_r($oldQuestions);
        // print_r($questions);
        foreach ($oldQuestions as $oldQuestion) {
            if (!in_array($oldQuestion, $questions)) {
                array_push($questionsToDelete, $oldQuestion);
            }
        }

        Question::destroy($questionsToDelete);

        foreach ($questions as $question) {
            if (!in_array($question, $oldQuestions)) {
                array_push($questionsToAdd, $question);
                Question::create(['question' => $question, 'cat_id' => $category->id ]);
            }
        }
        */
        
        $response['success'] = 'Detail has been updated! Chillax.';
        return $this->sendApiResponse($request->all());

    }

    public function destroy(Request $request,$id)
    {
        $status = config('enums.STATUS');
        $job = Category::where('id',$id)->update(['status' => $status['DEL']]);
        if($job)
        {
            $request->session()->flash('success', 'Job Is Deleted!');
            return redirect('/jobs');
        }
        else{
            $request->session()->flash('error', 'There Is Some Issue!');
            return redirect('/jobs');
        }
    }

    public function changeJobStatus(Request $request,$id) {
        $requestData = $request->all();
        
        $category = Category::find($id);
        $category->update($requestData);
        
        $response['success'] = 'Details have been saved! Chillax.';
        return $this->sendApiResponse($response);
    }
}

<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\User;
use App\JobLocation;
use App\Application;
use App\ApplicationActivity;
use App\Category;
use App\ApplicationMeta;
use Mail;
use App\Mail\ApplicantMail;
use App\Mail\PosterNewApplicationMail;
use App\Mail\UserResponded;

class ApplicantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $response = array('applications' => array());
        $org_id = $request->instance()->query('org_id');

        $extra_field = (request()->has('extra_field')) ? request()->extra_field : "";

        $response['applications'] = Application::with([
            'category' => function ($query) {
                $query->with([
                    'job_category', 'job_locations', 'functional_area',
                    'job_tags' => function ($q) {
                        $q->with(['tag']);
                        $q->where('status', '=', '1');
                    }
                ]);
            }, 'application_meta' => function ($query) {
                $query->where('status', '=', '1');
            }
        ])
            ->where('extra_field', '=', $extra_field)
            ->orderBy('id', 'DESC')
            ->where('org_id', '=', $org_id)
            ->get();
        return $this->sendApiResponse($response);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // echo '1';die;
        $validator = Validator::make($request->all(), [
            "name" => ['required', 'string', 'max:50'],
            "email" => ['required', 'string', 'max:50'],
            "job_id" => ['required', 'exists:categories,id'],
            "subject" => ['required', 'string', 'max:600'],
            "description" => ['required', 'string'],
            // "questions" => ['required', 'array', 'min:1'],
            // "questions.*.question_id" => ['required', 'max:250'],
            // "meta_keys" => ['array']
            // "questions.*.answer" => ['required', 'max:250'],
        ]);
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return $this->sendApiError($error, null, 422);
        }
        $isApplicationAvailable = array();
        $isApplicationAvailable = Application::where('from_email', '=', $request->email)->where('cat_id', '=', $request->job_id)->first();
        if ($isApplicationAvailable) {
            $error = $validator->errors()->first();
            return $this->sendApiError('You have already applied for this job.', null, 200);
        }
        $org_id = $request->org_id;
        $extra_field = (isset($request->extra_field) && !empty($request->extra_field)) ? $request->extra_field : "";
        $job = array();
        $job = Category::find($request->job_id);

        if ($job) {
            $job = $job->toArray();
            $user = User::where('email', '=', $job['email_filter'])->first();
        }

        // echo '<pre>';
        // return $this->sendApiResponse($user->email);die;
        // $user = json_decode(json_encode($user), true);
        $application = Application::create([
            'org_id' => $org_id,
            'cat_id' => $request->job_id,
            'subject' => $request->subject,
            'name' => $request->name,
            'text' => $request->description,
            'from_email' => $request->email,
            'to_email' => $job['email_filter'],
            'status' => '1',
            'type' => '3', // api 
            'extra_field' => $extra_field // for its only

        ]);
        if (isset($request->questions) && !empty($request->questions) && count($request->questions) > 0) {
            foreach ($request->questions as $question) {
                ApplicationActivity::create([
                    'app_id' => $application->id,
                    'question_id' => $question['question_id'],
                    'answer' => $question['answer']
                ]);
            }
        }
        if (isset($request->application_meta) && !empty($request->application_meta)) {
            foreach ($request->application_meta as $key => $meta) {
                ApplicationMeta::create([
                    'app_id' => $application->id,
                    'meta_key' => $key,
                    'meta_value' => $meta,
                    'status' => '1',
                ]);
            }
        }

        Mail::to($request->email)->send((new UserResponded('Application submitted', $request->name, $org_id)));
        Mail::to($user->email)->send((new PosterNewApplicationMail('You have new applicant',$user->email,$org_id)));

        return $this->sendApiResponse($application);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

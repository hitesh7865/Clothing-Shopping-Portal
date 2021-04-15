<?php

namespace App\Http\Controllers\Api;

use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\User;
use App\JobLocation;
use App\JobPreferredLocation;
use App\Application;
use App\ApplicationActivity;
use App\JobTag;
use Crypt;
// use App\Enums\Currency;
use App\Currency;


class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $org_id = $request->instance()->query('org_id');

        $response = array('job_categories' => array());
        $main_query = Category::where('status', '=', '1')
            ->where('status', '=', '1')
            ->orderBy('id', 'DESC')
            ->where('org_id', '=', $org_id);

        $response['job_categories'] = $main_query->get();
        return $this->sendApiResponse($response);
    }

    public function getActiveJobs(Request $request)
    {

        $org_id = $request->instance()->query('org_id');
        $response = array('jobs' => array());
        $location = (request()->has('location')) ? request()->location : "";
        $tags = (request()->has('tags')) ? request()->tags : "";
        $work_from_home = (request()->has('work_from_home')) ? request()->work_from_home : "";
        $working_hours = (request()->has('working_hours')) ? request()->working_hours : "";
        $job_category_id = (request()->has('job_category_id')) ? request()->job_category_id : "";
        $main_query = Category::with([
            'job_locations' => function ($query) {
                $query->where('status', '=', '1');
                // $query->whereHas('city', '=', 'ahmedabad');
            },
            'job_category' => function ($query) use ($job_category_id) {
            },
            'functional_area' => function ($query){
            },
            'job_tags' => function ($query) {
                $query->with(['tag' => function ($query) {
                }]);
                $query->where('status', '=', '1');
            }
        ]);


        if ($location) {
            $main_query->whereHas('job_locations', function ($q) use ($location) {
                $q->where('country', 'like', "%{$location}%");
            });

            // $main_query->leftJoin('job_locations as location','categories.id','=','location.job_id');
            // $main_query->where('location.city','=',$location);
        }
        if ($tags) {
            $main_query->whereHas('job_tags', function ($q) use ($tags) {
                $tags = explode(',', $tags);
                $q->whereIn('tag_id', $tags);
            });
        }
        if ($job_category_id) {
            $main_query->where('job_category_id', '=', $job_category_id);
        }
        if ($work_from_home) {
            $main_query->where('work_from_home', '=', $work_from_home);
        }
        if ($working_hours) {
            $main_query->where('working_hours', '=', $working_hours);
        }
        $main_query->where('categories.status', '=', '1');
        $main_query->where('org_id', '=', $org_id);
        $main_query->orderBy('created_at', 'DESC');
        $response['jobs'] = $main_query->get();

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
        $validator = Validator::make($request->all(), [
            "title" => ['required', 'string', 'max:150'],
            "user_name" => ['required', 'string', 'max:150'],
            //"description" => ['required', 'string'],
            // "locations" => ['required', 'array', 'min:1'],
            // "question_set_id" => ['required'],
            "email" => ['required', 'email'],
            // "organization_id" => ['required'],
            // "locations.*.address" => ['required', 'max:250'],
            // "locations.*.city" => ['required', 'max:250'],
            // "locations.*.country" => ['required', 'max:250'],
            "job_expiry_date" => ['required', 'date']
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return $this->sendApiError($error, null, 422);
        }
        $org_id = $request->org_id;
        // echo $org_id;die;

        $user = array();
        $user = User::with(['organizations'])
            //->where('email', '=', $request->email)
            ->orWhere('user_name', '=', $request->user_name)
            ->first();

        if(count($user) == 0) {
            $email_user = User::with(['organizations'])
            ->where('email', '=', $request->email)
            // ->orWhere('user_name', '=', $request->user_name)
            ->first();
            if(count($email_user) != 0) {
                return $this->sendApiError('email is already registered.', null, 422); 
            }

        }
        if (count($user) == 0) {
            $user = User::create([
                'fullname' => (isset($request->full_name) && !empty($request->full_name)) ? $request->full_name : $request->user_name,
                'user_name' => $request->user_name,
                'email' => $request->email,
                'password' => bcrypt(str_random(8)),
                'role_id' => '3',
                'organization_id' => $org_id
            ]);

            if ($user) {
                $user->organizations()->attach($org_id);
            }
        }
        if ($user->organizations[0]->id != $org_id) {
            return $this->sendApiError('email or user_name already exists.', null, 422);
        }
        $request->start_date_time = (isset($request->start_date_time)) ? $request->start_date_time : date('Y-m-d H:i:s');

        $job = Category::create([
            'title' => $request->title,
            'description' => $request->description,
            'subject_filter' => $request->subject_filter,
            'email_filter' => $request->email_filter,
            'question_set_id' => $request->question_set_id,
            'job_category_id' => $request->job_category_id,
            'functional_area' => $request->functional_area,
            'key_skills' => $request->key_skills,
            'working_hours' => $request->working_hours,
            'work_from_home' => $request->work_from_home,
            'number_of_vacancies' => $request->number_of_vacancies,
            'gender_preference' => $request->gender_preference,
            'minimum_age_group' => $request->minimum_age_group,
            'maximum_age_group' => $request->maximum_age_group,
            'education_qualification' => $request->education_qualification,
            'experience_range' => $request->experience_range,
            'job_benefits' => $request->job_benefits,
            'salary_currency' => $request->salary_currency,
            'minimum_salary' => $request->minimum_salary,
            'maximum_salary' => $request->maximum_salary,
            'organisation_name' => $request->organisation_name,
            'organisation_email' => $request->organisation_email,
            'organisation_description' => $request->organisation_description,
            'organisation_additional_contact_details' => $request->organisation_additional_contact_details,
            'created_by' => $user['id'],
            'org_id' => $org_id,
            'subject_filter' => $request->title,
            'email_filter' => $request->email,
            'start_date_time' => date('Y-m-d H:i:s', strtotime($request->start_date_time)),
            'end_date_time' => date('Y-m-d H:i:s', strtotime($request->job_expiry_date)),
            'status' => $request->status,
            'contact_details' => $request->contact_details
        ]);



        if ($job) {
            $request_locations = $request->locations;
            if (is_array($request_locations) && count($request_locations) > 0) {
                foreach ($request_locations as $location) {
                    $job_id = $job->id;
                    JobLocation::create([
                        'job_id' => $job_id,
                        'city_id' => (isset($location['city_id']) && !empty($location['city_id'])) ? $location['city_id'] : "",
                        'address' => (isset($location['address']) && !empty($location['address'])) ? $location['address'] : "",
                        'city' => (isset($location['city']) && !empty($location['city'])) ? $location['city'] : "",
                        'country' => $location['country'],
                        'country_code' => $location['country_code']
                    ]);
                }
            }
        }


        // Start Job preferred Location

        // if ($job) {
        //     $request_locations = $request->locations;
        //     foreach ($request_locations as $location) {
        //         $job_id = $job->id;
        //         JobPreferredLocation::create([
        //             'job_id' => $job_id,
        //             'city_id' => (isset($location['city_id']) && !empty($location['city_id'])) ? $location['city_id'] : "",
        //             'city_name' => (isset($location['city']) && !empty($location['city'])) ? $location['city'] : "" ,
        //             'status' => $request->status
        //         ]);
        //     }
        // }

        // End Job preferred Location        

        if ($job) {
            if (isset($request->tags) && is_array($request->tags) && count($request->tags) > 0) {
                foreach ($request->tags as $tag) {
                    $job_id = $job->id;
                    JobTag::create([
                        'job_id' => $job_id,
                        'tag_id' => $tag,
                    ]);
                }
            }
        }
        return $this->sendApiResponse($job);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $org_id = $request->instance()->query('org_id');

        $response = array('job' => array());
        $location = (request()->has('location')) ? request()->location : "";
        $job_category_id = (request()->has('job_category_id')) ? request()->job_category_id : "";
        $main_query = Category::with([
            'job_locations' => function ($query) {
                $query->where('status', '=', '1');
            },
            'job_category' => function ($query) use ($job_category_id) {
            },
            'functional_area' => function ($query) use ($job_category_id) {
            }, 'question_set' => function ($query) use ($job_category_id) {
                $query->with(['question_set_question' => function ($q) use ($job_category_id) {
                    $q->with(['questions' => function ($q) use ($job_category_id) {
                        $q->where('status', '=', '1');
                    }]);
                    $q->where('status', '=', '1');
                }]);
            },
            'job_tags' => function ($query) {
                $query->with(['tag' => function ($query) {
                }]);
                $query->where('status', '=', '1');
            }
        ]);
        // $main_query->where('categories.status', '=', '1');
        $main_query->where('org_id', '=', $org_id);
        $main_query->where('id', '=', $id);
        $response['job'] = $main_query->first();

        return $this->sendApiResponse($response);
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
        //echo '1';die;
        $validator = Validator::make($request->all(), [
            "job_id" => ['required'],
            "title" => ['required', 'string', 'max:150'],
            "user_name" => ['required', 'string', 'max:150'],
            //"description" => ['required', 'string'],
            "locations" => ['required', 'array', 'min:1'],
            // "question_set_id" => ['required'],
            "email" => ['required', 'email'],
            // "organization_id" => ['required'],
            // "locations.*.address" => ['required', 'max:250'],
            // "locations.*.city" => ['required', 'max:250'],
            "locations.*.country" => ['required', 'max:250'],
            "job_expiry_date" => ['required', 'date'],
            "status" => ['required', Rule::in(['1', '0', '2', '3', '4', '5']),]
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return $this->sendApiError($error, null, 422);
        }

        $org_id = $request->org_id;
        $user = User::with(['organizations'])
            ->where('email', '=', $request->email)
            ->orWhere('user_name', '=', $request->user_name)
            ->first();

        //dd($request->title);
        $job = Category::find($id);
        //dd($job);
        $job->title = $request->title;
        $job->description = $request->description;
        $job->working_hours = $request->working_hours;
        $job->work_from_home = $request->work_from_home;
        $job->number_of_vacancies = $request->number_of_vacancies;
        $job->gender_preference = $request->gender_preference;
        $job->minimum_age_group = $request->minimum_age_group;
        $job->maximum_age_group = $request->maximum_age_group;
        $job->functional_area = $request->functional_area;
        $job->key_skills = $request->key_skills;
        $job->education_qualification = $request->education_qualification;
        $job->experience_range = $request->experience_range;
        $job->job_benefits = $request->job_benefits;
        $job->salary_currency = $request->salary_currency;
        $job->minimum_salary = $request->minimum_salary;
        $job->maximum_salary = $request->maximum_salary;
        $job->organisation_name = $request->organisation_name;
        $job->organisation_email = $request->organisation_email;
        $job->organisation_description = $request->organisation_description;
        $job->organisation_additional_contact_details = $request->organisation_additional_contact_details;
        $job->question_set_id = $request->question_set_id;
        $job->job_category_id = $request->job_category_id;
        $job->created_by = $user['id'];
        $job->org_id = $org_id;
        $job->subject_filter = $request->title;
        $job->email_filter = $request->email;
        $job->end_date_time = date('Y-m-d H:i:s', strtotime($request->start_date_time));
        $job->end_date_time = date('Y-m-d H:i:s', strtotime($request->job_expiry_date));
        $job->status = $request->status;  //  status = ?? After update Moderator will review
        $job->contact_details = $request->contact_details;
        $job->update();

        if ($job) {
            $request_locations = $request->locations;
            JobLocation::where('job_id', '=', $id)->update(['status' => 2]);
            foreach ($request_locations as $location) {
                $job_id = $job->id;
                JobLocation::create([
                    'job_id' => $job_id,
                    'city_id' => (isset($location['city_id']) && !empty($location['city_id'])) ? $location['city_id'] : "",
                    'address' => (isset($location['address']) && !empty($location['address'])) ? $location['address'] : "",
                    'city' => (isset($location['city']) && !empty($location['city'])) ? $location['city'] : "",
                    'country' => $location['country'],
                    'country_code' => $location['country_code']
                ]);
            }
        }

        if ($job) {
            JobTag::where('job_id', '=', $id)->update(['status' => 2]);
            foreach ($request->tags as $tag) {
                $job_id = $job->id;
                JobTag::create([
                    'job_id' => $job_id,
                    'tag_id' => $tag,
                ]);
            }
        }
        return $this->sendApiResponse($job);
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

    public function changeJobStatus(Request $request)
    {
        $job = Category::find($request->job_id);
        $job->status = $request->status;
        $job->save();

        return $this->sendApiResponse($job, 'job status updated successfully.');
    }

    // public function addApplicant(Request $request) {
    //     $validator = Validator::make($request->all(), [
    //         "name" => ['required', 'string', 'max:50'],
    //         "email" => ['required', 'string', 'max:50'],
    //         "job_id" => ['required'],
    //         "subject" => ['required', 'string','max:600'],
    //         "description" => ['required', 'string'],
    //         "questions" => ['required', 'array', 'min:1'],
    //         "questions.*.question_id" => ['required', 'max:250'],
    //         // "questions.*.answer" => ['required', 'max:250'],
    //     ]);

    //     if ($validator->fails()) {
    //         $error = $validator->errors()->first();
    //         return $this->sendApiError($error, null, 422);
    //     }
    //     $org_id = '11'; 
    //     $job = Category::find($request->job_id);
    //     $application = Application::create([
    //         'org_id' => $org_id,
    //         'cat_id' => $request->job_id,
    //         'subject' => $request->subject,
    //         'name' => $request->name,
    //         'text' => $request->description,
    //         'from_email' => $request->email,
    //         'to_email' => $job->email_filter,
    //         'status' => '1',
    //         'type' => '3'// api 
    //     ]);

    //     foreach($request->questions as $question) {
    //         ApplicationActivity::create([
    //             'app_id' => $application->id,
    //             'question_id' => $question['question_id'],
    //             'answer' => $question['answer']
    //         ]);
    //     }
    //     return $this->sendApiResponse($application);
    // }

    //poster list 

    public function getJobPosterList(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "user_name" => ['required', 'string', 'max:50'],
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return $this->sendApiError($error, null, 422);
        }
        $org_id = $request->org_id;
        $response = array('jobs' => array());
        $user = array();
        $user = User::where('user_name', '=', $request->user_name)->first();
        // echo '<pre>';print_r($user['id']);die;

        $main_query = Category::with([
            'job_locations' => function ($query) {
                $query->where('status', '=', '1');
            },
            'job_category' => function ($query) {
            },
            'functional_area' => function ($query) {
            },
            'job_tags' => function ($query) {
                $query->with(['tag' => function ($query) {
                }]);
                $query->where('status', '=', '1');
            }
        ]);
        $main_query->withCount(['applications']);

        $main_query->where('org_id', '=', $org_id);
        $main_query->where('created_by', '=', $user['id']);
        $main_query->orderBy('id', 'DESC');
        // if ($user) {
        $response['jobs'] = $main_query->get();
        // }
        if (count($response['jobs']) > 0) {
            foreach ($response['jobs'] as $key => $job) {
                $response['jobs'][$key]['encrypted_job_id'] = Crypt::encryptString($job->id);
            }
        }

        return $this->sendApiResponse($response);
    }

    public function getLocations(Request $request)
    {
        $org_id = $request->instance()->query('org_id');
        $locations = JobLocation::whereHas('categories', function ($query) use ($org_id) {
            $query->where('org_id', $org_id);
            $query->where('status', '1');
        })
            ->where('status', '=', '1')
            ->orderBy('country', 'ASC')
            ->groupBy('country')
            ->get();

        return $this->sendApiResponse($locations, "", 200);
    }

    public function getCurrencyCode()
    {
        $currencies = Currency::where('status', '1')->get();
        return $this->sendApiResponse($currencies, "", 200);
    }

    public function getCountries()
    {
        $countries = $this->getApiCountries();
        $countries = json_decode($countries);
        return $this->sendApiResponse($countries, "", 200);
    }

    public function getCitiesByCountry($code)
    {
        $cities = $this->getApiCitiesByCountry($code);
        $cities = json_decode($cities);
        return $this->sendApiResponse($cities, "", 200);
    }
}

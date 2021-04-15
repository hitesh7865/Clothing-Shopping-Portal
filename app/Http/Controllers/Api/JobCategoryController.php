<?php

namespace App\Http\Controllers\Api;

use App\JobCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class JobCategoryController extends Controller
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
        $response['job_categories'] = JobCategory::where('status', '=', '1')
            ->where('org_id', '=', $org_id)
            ->orderBy('name', 'ASC')
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

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:250'],
            'org_id' => ['required'],
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return $this->sendApiError($error, null, 422);
        }
        $org_id = $request->org_id;
        $jobCategory = new JobCategory();
        $jobCategory->name = $request->name;
        $jobCategory->org_id = $org_id;
        $jobCategory->status = '1';
        $jobCategory->created_by = '13';
        $jobCategory->save();

        return $this->sendApiResponse($jobCategory, 'Job Category created successfully.', 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\JobCategory  $jobCategory
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $response = JobCategory::where('id', '=', $id)->where('status', '=', '1')->first();
        return $this->sendApiResponse($response);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\JobCategory  $jobCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(JobCategory $jobCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\JobCategory  $jobCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);

        $jobCategory = JobCategory::find($id);
        $jobCategory->name = $request->input('name');
        $jobCategory->save();

        if ($jobCategory) {
            return $this->sendApiResponse($jobCategory, 'quiz updated successfully.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\JobCategory  $jobCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $jobCategory = JobCategory::find($id);
        $jobCategory->status = '2';
        $jobCategory->save();

        return $this->sendApiResponse(array(), 'quiz deleted successfully.');
    }
}

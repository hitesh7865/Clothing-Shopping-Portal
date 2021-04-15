<?php

namespace App\Http\Controllers\Api;

use App\QuestionSet;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class QuestionSetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $org_id = $request->instance()->query('org_id');
        $response = array('question_sets' => array());
        $response['question_sets'] = QuestionSet::whereHas('question_set_question', function ($query) {
            $query->where("status", "=", "1");
        })->where('status', '=', '1')
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

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:250'],
            'org_id' => ['required'],
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return $this->sendApiError($error, null, 422);
        }
        $org_id = $request->org_id;
        $questionSet = new QuestionSet();
        $questionSet->name = $request->name;
        $questionSet->org_id = $org_id;
        $questionSet->status = '1';
        $questionSet->created_by = '13';
        $questionSet->save();

        return $this->sendApiResponse($questionSet, 'Question Set created successfully.', 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\QuestionSet  $questionSet
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$id)
    {
        $org_id = $request->org_id;
        $response = array('question_sets' => array());
        $response['question_sets'] = QuestionSet::with([
            'question_set_question' => function ($query) {
                $query->with(['questions' => function ($q) {
                    $q->where('status', '=', '1');
                }]);
                $query->where('status', '=', '1');
            }
        ])
            ->where('status', '=', '1')
            ->where('org_id', '=', $org_id)
            ->where('id', '=', $id)
            ->first();
        return $this->sendApiResponse($response);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\QuestionSet  $questionSet
     * @return \Illuminate\Http\Response
     */
    public function edit(QuestionSet $questionSet)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\QuestionSet  $questionSet
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, QuestionSet $questionSet)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\QuestionSet  $questionSet
     * @return \Illuminate\Http\Response
     */
    public function destroy(QuestionSet $questionSet)
    {
        //
    }
}

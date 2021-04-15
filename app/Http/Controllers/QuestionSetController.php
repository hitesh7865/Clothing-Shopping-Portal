<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Organization;
use App\Category;
use App\Mailbox;
use App\Question;
use App\QuestionSet;
use App\QuestionSetQuestion;
use Bugsnag;
use Datatables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class QuestionSetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('questionset.list');
        
    }

    public function getQuestionSets()
    {
        $organization = Organization::where('user_id', Auth::user()->id)->first();

        $question_sets = QuestionSet::where('org_id', Auth::user()->organization_id)->whereNotIn('status',[5])->orderBy('updated_at', 'desc');
        return DataTables::of($question_sets)
            ->addColumn('action', function ($question_set) {
                return '<a href="/question-sets/' . $question_set->id . '/edit' . '" class="btn btn_xs btn_blue">Edit</a>
                <form id="delete-question-set' . $question_set->id . '" action="' . route('question-sets.destroy', $question_set->id) . '" method="POST" class="">
                   <input type="hidden" name="_token" value="' . csrf_token() . '">
                   <input type="hidden" name="_method" value="delete" />
                   <button type="submit" class="btn btn_xs btn_red">
                    Delete
                    </button>
                </form>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('questionset.create');
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $organization = Organization::where('user_id', Auth::user()->id)->first();
        $org_id = Auth::user()->organization_id;

        $validator = Validator::make($request->all(), [
            'name' => [
                'required', 'string', 'max:250',
                Rule::unique('question_sets')->where(function ($query) use ($org_id) {
                    return $query->where('status', '1')->where('org_id', '=', $org_id);
                })
            ],
        ]);
        if ($validator->fails()) {
            $error = $validator->errors();
            return redirect()->back()->with('errors', $error);
        }
        $requestData = $request->all();
        $requestData['org_id'] = Auth::user()->organization_id;
        $requestData['created_by'] = Auth::user()->id;
        $requestData['status'] = 1;

        $tag = QuestionSet::create($requestData);
        
        $request->session()->flash('success', 'Details have been saved! Chillax.');
        return redirect('question-sets');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $organization = Organization::where('user_id', Auth::user()->id)->first();
        // Get Questions
        $question_set = QuestionSet::find($id)->toArray();
        return response()->view('questionset.create', ['data' => $question_set]);
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
        $org_id = Auth::user()->organization_id;
        $validator = Validator::make($request->all(), [
            'name' => [
                'required', 'string', 'max:250',
                Rule::unique('question_sets')->where(function ($query) use ($org_id,$id) {
                    return $query->where('status', '1')->where('org_id', '=', $org_id)->where('id','!=',$id);
                })
            ],
        ]);

        if ($validator->fails()) {
            $error = $validator->errors();
            return redirect()->back()->with('errors', $error);
        }
        $questionset = QuestionSet::where('id', $id)->first();
        $requestData = $request->all();
        $questionset->update($requestData);
        $request->session()->flash('success', 'Question Set has been updated!');
        return redirect('/question-sets');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        $status = config('enums.STATUS');
        $jobs = QuestionSet::where('id',$id)->update(['status' => $status['DEL']]);

        if($jobs)
        {
            $request->session()->flash('success', 'Question Set has been deleted!');
            return redirect('/question-sets');
        }
        else
        {
            $request->session()->flash('error', 'There Is Some Issue!');
            return redirect('/question-sets');
        }   
    }
}

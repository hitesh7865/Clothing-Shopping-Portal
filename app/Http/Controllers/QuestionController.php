<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Enums\CategoryStatus;
use App\Enums\QuestionStatus;
use App\Enums\QuestionType;


use App\User;
use App\Organization;
use App\Category;
use App\Question;
use App\QuestionSetQuestion;
use App\QuestionSet;
use Datatables;

class QuestionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $organization = Organization::where('user_id', Auth::user()->id)->first();
        $categories = Category::select("id", "title")->where("org_id", Auth::user()->organization_id)->get()->toArray();
        $statuses = QuestionStatus::LIST_ALL;
        array_unshift($categories, ["id" => -1, "title" => "All"]);
        array_unshift($statuses, ["id" => -1, "value" => "All"]);


        $categoryId = $request->input("category");
        $jobStatusId = $request->input("status");
        return view('question_list', [
            'categories' => $categories,
            'statuses' => $statuses,
            'category_id' => $categoryId,
            'job_status_id' => $jobStatusId
        ]);
    }

    public function getQuestions(Request $request)
    {
        $categoryId =  $request->input("category") == "-1" ? "%" : $request->input("category");
        $jobStatusId = $request->input("status") == "-1" ? "%" : $request->input("status");


        $organization = Organization::where('user_id', Auth::user()->id)->first();
        // dd(Question::get()->toArray());

        // $questions = DB::table('questions')
        //     ->join('categories', 'categories.id', '=', 'questions.cat_id')
        //     ->where('categories.org_id', Auth::user()->organization_id)
        //     ->where('categories.id', 'LIKE', $categoryId)
        //     ->where('questions.status', '<>', -1)
        //     ->select('categories.id as cat_id', 'categories.title', 'questions.id', 'questions.question')
        //     ->get();
        //
        /*
        $questions = DB::table('questions')
                ->where('questions.org_id', Auth::user()->organization_id)
                ->where('questions.status', '<>', -1)
                ->get();
          */
        $questions = Question::where('org_id', Auth::user()->organization_id)->whereNotIn('status',[5])->orderBy('updated_at', 'desc');
            

        return DataTables::of($questions)
            ->addColumn('action', function ($question) {
                return '<a href="/questions/' . $question->id . "/edit" . '" class="btn btn_xs btn_blue">Edit</a>
                <form id="delete-questions' . $question->id . '" action="' . route('questions.destroy', $question->id) . '" method="POST" class="">
                   <input type="hidden" name="_token" value="' . csrf_token() . '">
                   <input type="hidden" name="_method" value="delete" />
                   <button type="submit" class="btn btn_xs btn_red">
                    Delete
                    </button>
                </form>';
            })
            ->addColumn('type', function ($question) {
                switch ($question->type) {
                    case \App\Enums\QuestionType::RANGE:
                        return "Range";
                        break;
                    case \App\Enums\QuestionType::MULTI_OPTION:
                        return "Multi Option";
                        break;
                    case \App\Enums\QuestionType::TEXT:
                        return "Text";
                        break;
                    case \App\Enums\QuestionType::LONG_TEXT:
                        return "Text";
                        break;
                    case \App\Enums\QuestionType::DROPDOWN:
                        return "Text";
                        break;
                    default:
                        return "";
                }
            })
            ->addColumn('options', 'yajra.question_options')
            ->addColumn('positives', 'yajra.question_positives')
            ->addColumn('negatives', 'yajra.question_negatives')
            ->rawColumns(['options', 'positives', 'negatives', 'action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Get
        $organization = Organization::where('user_id', Auth::user()->id)->first();
        // $freeCategories = Category::getFreeCategoriesByOrg(Auth::user()->organization_id);
        $freeCategories = Category::where('org_id', Auth::user()->organization_id)->where('status', CategoryStatus::ACTIVE)->get();
        $question_sets = QuestionSet::where('org_id', Auth::user()->organization_id)->where('status', CategoryStatus::ACTIVE)->get();

        // dd($freeCategories);
        return response()->view('question', ['data' => ['categories' => $freeCategories, 'question_sets' => $question_sets]]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Question $question, Request $request)
    {
        $organization = Organization::where('user_id', Auth::user()->id)->first();
        $freeCategories = Category::where('org_id', Auth::user()->organization_id)->where('status', CategoryStatus::ACTIVE)->get();
        $question_sets = QuestionSet::where('org_id', Auth::user()->organization_id)->where('status', CategoryStatus::ACTIVE)->get();

        $questionSetQuestion = QuestionSetQuestion::where('question_id', '=', $question['id'])
            ->where('status', '=', '1')->pluck('question_set_id')->toArray();

        // $question = Question::where('id', $id)->first();
        $question['categories'] = $freeCategories;
        $question['question_sets'] = $question_sets;
        $question['question_set_question'] = $questionSetQuestion;
        // echo '<pre>';
        // print_r($questionSetQuestion);
        // die;
        return response()->view('question', ['data' => $question]);
    }

    public function store(Request $request)
    {
        $organization = Organization::where('user_id', Auth::user()->id)->first();
        $requestData = $request->all();
        $requestData['org_id'] = Auth::user()->organization_id;
        $question = Question::create($requestData);
        if (isset($request->question_sets) && !empty($request->question_sets)) {
            foreach ($request->question_sets as $question_set) {
                QuestionSetQuestion::create([
                    'question_id' => $question->id,
                    'question_set_id' => $question_set,
                ]);
            }
        }
        $request->session()->flash('success', 'A new Question has been stored.');
        return redirect('/questions');
    }
    public function update(Question $question, Request $request)
    {
        $requestData = $request->all();
        // echo '<pre>';
        // print_r($requestData['question_sets']);
        // die;
        if (empty($requestData['options'])) {
            $requestData['options'] = "";
        }
        if (empty($requestData['positives'])) {
            $requestData['positives'] = "";
        }
        if (empty($requestData['negatives'])) {
            $requestData['negatives'] = "";
        }

        if (isset($request->question_sets) && !empty($request->question_sets)) {
            QuestionSetQuestion::where('question_id', '=', $requestData['id'])->update(['status' => 2]);
            // QuestionSetQuestionDB::delete('delete users where name = ?', ['John']);
            foreach ($request->question_sets as $question_set) {
                QuestionSetQuestion::create([
                    'question_id' => $requestData['id'],
                    'question_set_id' => $question_set,
                ]);
            }
        }
        $question->update($requestData);
        $request->session()->flash('success', 'Question has been updated.');
        return redirect('/questions');
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
        $jobs = Question::where('id',$id)->update(['status' => $status['DEL']]);

        if($jobs)
        {
            $request->session()->flash('success', 'Question has been deleted!');
            return redirect('/questions');
        }
        else
        {
            $request->session()->flash('error', 'There Is Some Issue!');
            return redirect('/questions');
        }
    }
}

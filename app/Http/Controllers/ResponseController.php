<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use App\User;
use App\Organization;
use App\Setting;
use App\Application;
use App\ApplicationActivity;
use App\Category;
use Crypt;
use Mail;
use App\Mail\UserResponded;
use App\Enums\ApplicationStatus;

class ResponseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $appId;
        if (!empty($request->input('token'))) {
            $appId = Crypt::decrypt($request->input('token'));
        } else {
            abort(404, "Page not found");
        }
        if ($appId == null) {
            return redirect(route("response.expired"));
        }
        $application = Application::where('id', $appId)
            ->first();

        if ($application->status != ApplicationStatus::QUESTIONED) {
            return redirect(route("response.expired"));
        }
        $category = Category::where('id', $application->cat_id)
            ->where('status', 1)
            ->first();

        if (!$category) {
            return redirect(route("response.expired"));
        }
        $questions = $category->questions()->get()->toArray();
        return view('response', ['questions' => $questions]);
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
        $appId;
        if (!empty($request->input('app_token'))) {
            $appId = Crypt::decrypt($request->input('app_token'));
        } else {
            abort(404, "Page not found");
        }
        $application = Application::where('id', $appId)
            ->first();
        $category = Category::where('id', $application->cat_id)
            ->where('status', 1)
            ->first();
        $questions = $category->questions()->get()->toArray();
        foreach ($questions as $question) {
            ApplicationActivity::create([
                'app_id' => $appId,
                'question_id' => $question['id'],
                'answer' => $request->input('question_' . $question['id']),
                'status' => 1
            ]);
        }

        Application::where('id', $appId)->update(['status' =>  ApplicationStatus::RESPONDED]);

        // Send a thank you email
        $settings = Setting::where('id', $application->org_id)->first();
        if ($settings && $settings->send_thankyou_email == 1) {
            Mail::to($application->from_email)->send((new UserResponded('Re : ' . $application->subject, 'there')));
        }


        return redirect('/response/thanks');
    }
    public function thanks()
    {
        return view('response_thanks');
    }
    public function expired()
    {
        return view('response_expired');
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

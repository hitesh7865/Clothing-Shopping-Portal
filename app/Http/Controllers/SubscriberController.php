<?php

namespace App\Http\Controllers;

use App\Subscriber;
use Illuminate\Http\Request;
use Mail;
use URL;

class SubscriberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = array('name' => "bhavik","email"=>"bhavik@test.com" );

        return Subscriber::get();
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
        $data = $request->input();
        $this->sendEmail($data);
        $user_insert = Subscriber::create($data);
        $request->session()->flash('success', 'Thank you for signing up. We will reach you soon.');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\NewsLetterContestSubscriber  $newsLetterContestSubscriber
     * @return \Illuminate\Http\Response
     */
    public function show(Subscriber $newsLetterContestSubscriber)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\NewsLetterContestSubscriber  $newsLetterContestSubscriber
     * @return \Illuminate\Http\Response
     */
    public function edit(Subscriber $newsLetterContestSubscriber)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\NewsLetterContestSubscriber  $newsLetterContestSubscriber
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Subscriber $newsLetterContestSubscriber)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\NewsLetterContestSubscriber  $newsLetterContestSubscriber
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subscriber $newsLetterContestSubscriber)
    {
        //
    }

    public function exportCSV()
    {
        $news_letter_subscribers = Subscriber::get();
        // echo "<pre>";
        // print_r($news_letter_subscribers);
        // exit();
        header('Content-Type: application/csv');
        header('Content-Disposition: attachment; filename="subscribers.csv";');
        $file = fopen('php://output', 'w');
        $headers = ['Id', 'Name', 'Email','Phone','Type', 'URL', 'Created On', 'Updated At'];
        fputcsv($file, $headers);

        foreach ($news_letter_subscribers as $subscriber) {
            $subscriber = $subscriber->toArray();
            fputcsv($file, $subscriber);
        }
        fpassthru($file);
    }

    public function sendEmail($data){

        $admin_emails = explode(',', env('ADMIN_EMAIL') ); 
        $data["baseurl"] = URL::to('/');
        $user_name = $data['name'];

        Mail::send('emails.new_signup', $data, function($message) use ($admin_emails,$user_name)
        {
            foreach ($admin_emails as $key => $email) {
                $message->to($email)->subject($user_name.' has just signed up on Acloudery.');
            }
        });
    }
}

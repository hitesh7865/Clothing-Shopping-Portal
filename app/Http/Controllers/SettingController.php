<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Organization;
use App\Setting;
use Crypt;
use phpseclib\Crypt\RSA;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        return redirect('/settings/organization');
    }
    public function showTab()
    {
        $organization = Organization::where('user_id', Auth::user()->id)->first();
        $settingsData = Setting::where('org_id', Auth::user()->organization_id)->first();
        $user = Auth::user();

        $tabs = [
            [
                "title" => "Organization",
                "link" => "/settings/organization",
                'view' => "partials.settings.organization",
                'data' => $organization
            ],
            [
                "title" => "Profile",
                "link" => "/settings/profile",
                'view' => "partials.settings.profile",
                'data' => $user
            ],
            // [
            //   "title" => "SMTP",
            //   "link" => "/settings/smtp",
            //   'view' => "partials.settings.smtp",
            //   'data' => $settingsData
            // ],
            [
                "title" => "Candidate",
                "link" => "/settings/candidate",
                'view' => "partials.settings.candidates",
                'data' => $settingsData
            ]
        ];
        return response()->view('settings', ['tabs' => $tabs]);
    }

    public function saveOrganization(Request $request)
    {
        $user = Auth::user();
        $organization = Organization::where('user_id', $user->id)->first();
        $requestData = $request->all();

        $requestData['user_id'] = $user->id;

        if ($organization) {
            $organization->update($requestData);
        } else {
            Organization::create($requestData);
        }

        $request->session()->flash('success', 'Organization details have been saved');
        return back()->withInput();
    }
    public function store(Request $request)
    {
        $user = Auth::user();
        $organization = Organization::where('user_id', $user->id)->first();
        $organizationSettings = Setting::where('org_id', Auth::user()->organization_id)->first();
        $requestData = $request->all();

        if (empty($requestData['send_reminder_email_days'])) {
            $requestData['send_reminder_email_days'] = 0;
        }
        if (empty($requestData['send_thankyou_email'])) {
            $requestData['send_thankyou_email'] = 0;
        } else {
            if ($requestData['send_thankyou_email'] == "on") {
                $requestData['send_thankyou_email'] = 1;
            } else {
                $requestData['send_thankyou_email'] = 0;
            }
        }

        $requestData['org_id'] = Auth::user()->organization_id;
        Setting::create($requestData);
        $request->session()->flash('success', 'Settings has been updated');
        return back()->withInput();
    }
    public function update(Setting $organizationsetting, Request $request)
    {
        $requestData = $request->all();
        if (empty($requestData['send_reminder_email_days'])) {
            $requestData['send_reminder_email_days'] = 0;
        }
        if (empty($requestData['send_thankyou_email'])) {
            $requestData['send_thankyou_email'] = 0;
        } else {
            if ($requestData['send_thankyou_email'] == "on") {
                $requestData['send_thankyou_email'] = 1;
            } else {
                $requestData['send_thankyou_email'] = 0;
            }
        }
        // $organizationSettings = Setting::where('id', $request->input("id"))->first();

        $organizationsetting->update($requestData);
        $request->session()->flash('success', 'Settings has been updated');
        return back()->withInput();
    }
    public function saveImapSmptp(Request $request)
    {
        $user = Auth::user();
        $organization = Organization::where('user_id', $user->id)->first();
        $organizationSettings = Setting::where('org_id', Auth::user()->organization_id)->first();
        $requestData = $request->all();
        $requestData['imap_connection_type'] = 'gmail';
        $requestData['org_id'] = Auth::user()->organization_id;

        if (isset($requestData['imap_password'])) {
            $requestData['imap_password'] =  Crypt::encrypt($requestData['imap_password']);
        }

        if (isset($requestData['smtp_password'])) {
            $requestData['smtp_password'] =  Crypt::encrypt($requestData['smtp_password']);
        }

        if ($organization && $organizationSettings) {
            $organizationSettings->update($requestData);
        } else {
            Setting::create($requestData);
        }
        $request->session()->flash('success', 'IMAP/SMTP settings has been saved');
        return back()->withInput();
    }

    public function genrate_api_token(){
        $user = Auth::user();
        $organization = Organization::where('user_id', $user->id)->first();
        $response = array('api_key' => '');
        $response['api_key'] = $api_key = Crypt::encrypt(str_random(16));
        $organization = Organization::find(Auth::user()->organization_id);
        $organization->api_key = $api_key;
        $organization->save();
        return $this->sendApiResponse($response);
    }
    public function disableExpiredJobs(Request $request)
    {
        Organization::disableExpiredJobs();
    }
}

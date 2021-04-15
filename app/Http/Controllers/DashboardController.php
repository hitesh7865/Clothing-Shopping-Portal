<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Organization;
use App\Application;
use App\ApplicationActivity;
use App\Category;
use App\Mailbox;
use App\Enums\ApplicationStatus;
use DB;

class DashboardController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $screenedCandidates = $this->getCandidatesListForHiring();
        $topCandidates = $this->getTopCandidatesList();
        $jobs = $this->getCountOfJobsByStatus();
        $candidates = $this->getCandidateByStatus();
        // dd($jobs);

        if (Auth::user()->role_id == 2) {
          
            $view = 'dashbord.moderator';
        }
        else if (Auth::user()->role_id == 3) {
           
            $view = 'dashbord.job_poster';
        }
        else{
            $view = 'dashboard';
        }
        return view($view, [
          'screened_candidates' =>$screenedCandidates,
          'top_candidates' => $topCandidates,
          'jobs' => $jobs,
          'candidates' => $candidates
        ]);
    }
    private function getCandidatesListForHiring()
    {
        $organization = Organization::where('user_id', Auth::user()->id)->first();
        // echo '<pre>';
        // print_r(Auth::user()->id);
        // die;
        $categories = Category::select('categories.title', 'categories.id as cat_id', DB::raw('count(*) as applicant_count'))
                      ->where("applications.org_id", Auth::user()->organization_id)
                      ->join('applications', 'categories.id', '=', 'applications.cat_id')
                      ->where('categories.status', 1)
                      ->where('applications.status', ApplicationStatus::SCREENED)
                      ->groupBy('title')
                      ->groupBy('categories.id')
                      ->limit(5)
                      ->get();
        return $categories;
    }
    private function getTopCandidatesList()
    {
        $organization = Organization::where('user_id', Auth::user()->id)->first();
        $applications = Category::select('categories.title', 'categories.id as cat_id', 'applications.rating', 'applications.from_email', 'applications.id as app_id')
                      ->where("applications.org_id", Auth::user()->organization_id)
                      ->join('applications', 'categories.id', '=', 'applications.cat_id')
                      ->where('categories.status', 1)
                      ->where('applications.status', ApplicationStatus::RESPONDED)
                      ->orderBy('applications.rating', 'DESC')
                      ->limit(5)
                      ->get();
        return $applications;
    }
    private function getCountOfJobsByStatus()
    {
        $organization = Organization::where('user_id', Auth::user()->id)->first();
        $activeJobs = Category::select('status')
                      ->where("org_id", Auth::user()->organization_id)
                      ->where('status', 1)
                      ->get()->count();
        $deadJobs = Category::select('status')
                                    ->where("org_id", Auth::user()->organization_id)
                                    ->where('status', 0)
                                    ->get()->count();
        return ['open' => $activeJobs , 'closed' => $deadJobs];
    }
    private function getCandidateByStatus()
    {
        $organization = Organization::where('user_id', Auth::user()->id)->first();
        $applications = Application::select('applications.status', DB::raw('count(*) as applicant_count'))
                      ->where("applications.org_id", Auth::user()->organization_id)
                      ->groupBy('applications.status')
                      ->get()->toArray();
        foreach ($applications as $key => $application) {
            $application['title'] = ApplicationStatus::getKeyById($application['status']);
            $application['route'] = route('candidates') . "?status=" . $application['status'];
            $applications[$key] = $application;
        }
        return $applications;
    }
}

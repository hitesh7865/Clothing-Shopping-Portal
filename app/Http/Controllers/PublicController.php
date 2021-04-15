<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use Crypt;

class PublicController extends Controller
{
    public function showPosterJobDetail($id)
    {
        $job_id = $id;
        // $id = Crypt::decryptString($id);
        // echo $job_id ;die;
        $job = Category::with([
            'job_locations' => function ($query) {
                $query->where('status', '=', '1');
            },
            'job_category', 'question_set' => function ($query) {
                $query->with(['question_set_question' => function ($q) {
                    $q->with(['questions' => function ($q) {
                        $q->where('status', '=', '1');
                    }]);
                    $q->where('status', '=', '1');
                }]);
            },
            'job_tags' => function ($query) {
                $query->with(['tag' => function ($query) {
                }]);
                $query->where('status', '=', '1');
            }, 'applications' => function($query) {
                $query->with(['application_activities' => function ($q) {
                    $q->with(['question' => function ($q) {
                        $q->where('status', '=', '1');
                    }]);
                    $q->where('status', '=', '1');
                }]);
                $query->where('status', '=', '1');
            }
        ])->where('id', '=', $id)->first();
            // echo '<pre>';
            // print_r(json_encode($job));
            // die;
        return view('public_pages.public_job_detail', array('job' => $job));
    }
}

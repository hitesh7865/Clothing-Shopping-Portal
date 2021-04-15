<?php

namespace App\Providers;

use View;
use App\User;

use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
  /**
   * Bootstrap any application services.
   *
   * @return void
   */
  public function boot(Request $request)
  {

    // print_r($request->user());
    // die;
  

    
 
    Schema::defaultStringLength(191);


    view()->composer('*', function ($view) 
    {

      if (Auth::check())
      {
        $role = config('enums.ROLE');
        // echo "<pre>";
        // print_r($role['MODERATOR']);
       // die;
      if (Auth::user()->role_id == $role['JOB_POSTER'] || Auth::user()->role_id == $role['MODERATOR']) {
        $items = [
          [
            "link" => "/dashboard",
            "text" => "Dashboard",
          ],
          [
            "link" => "/candidates",
            "text" => "Candidates",
          ],
         
          [
            "link" => "/jobs",
            "text" => "Jobs",
          ],

         

        ];
        if (Auth::user()->role_id == $role['MODERATOR']) {
         
        $pending_moderator =
          [
            "link" => "/pending-moderator",
            "text" => "Pending Request",
          ];

        array_push($items,$pending_moderator);

        }
      }
      else{
      $items = [
        [
          "link" => "/dashboard",
          "text" => "Dashboard",
        ],
        [
          "link" => "/candidates",
          "text" => "Candidates",
        ],
        [
          "link" => "/organization/users",
          "text" => "Users",
        ],
        [
          "link" => "/jobs",
          "text" => "Jobs",
        ],
        [
          "link" => "/job-categories",
          "text" => "Job Categories",
        ],
        [
          "link" => "/questions",
          "text" => "Questions",
        ],
        [
          "link" => "/mailboxes",
          "text" => "MailBoxes",
        ],
        [
          "link" => "/question-sets",
          "text" => "Question Sets",
        ],
        [
          "link" => "/tags",
          "text" => "Tags",
        ],
        
        [
        "link" => "/functional-areas",
          "text" => "Functional Area ",
        ],
        [
          "link" => "/settings",
          "text" => "Settings",
          "child" => [
            [
              "link" => "/settings/organization",
              "text" => "Organization",
            ],
            [
              "link" => "/settings/profile",
              "text" => "Profile",
            ],
            // [
            //   "link" => "/settings/smtp",
            //   "text" => "SMTP",
            // ],
            [
              "link" => "/settings/candidate",
              "text" => "Candidate",
            ]
          ]
        ]
      ];

    }

        
        $view->with('sidenav_items', $items );    
  }
    });  
   
    
    //View::share('sidenav_items', $items);
  }

  /**
   * Register any application services.
   *
   * @return void
   */
  public function register()
  {
    //
    $this->app->alias('bugsnag.logger', \Illuminate\Contracts\Logging\Log::class);
    $this->app->alias('bugsnag.logger', \Psr\Log\LoggerInterface::class);
  }
}

<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\User;
use App\Organization;

use App\Jobs\FetchJobs;
use App\Jobs\SendReminderEmailToApplicants;
use App\Jobs\DisableExpiredJobs;
use App\Jobs\GenerateApplicantRatings;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $this->scheduleDeletionExpiredJobs($schedule);
        $this->scheduleApplicantReminders($schedule);
        $this->scheduleFetchJobs($schedule);
        $this->scheduleRatingGenerationJobs($schedule);
    }

    private function scheduleFetchJobs($schedule)
    {
        $schedule->call(function () {
            $organizations = Organization::where("status", 1)->get();
            foreach ($organizations as $organization) {
                $job = (new FetchJobs($organization->user_id))->onQueue(env('QUEUE_SHELL'));
                dispatch($job);
            }
        })->name("fetch_everything_for_everyone")->withoutOverlapping()->hourly();
    }
    private function scheduleApplicantReminders($schedule)
    {
        $schedule->call(function () {
            $organizations = Organization::where("status", 1)->get();
            foreach ($organizations as $organization) {
                $job = (new SendReminderEmailToApplicants($organization->user_id))->onQueue(env('QUEUE_SHELL'));
                dispatch($job);
            }
        })->name("send_reminders_to_applicants")->withoutOverlapping()->daily();
    }
    private function scheduleDeletionExpiredJobs($schedule)
    {
        $schedule->call(function () {
            $organizations = Organization::where("status", 1)->get();
            foreach ($organizations as $organization) {
                $job = (new DisableExpiredJobs())->onQueue(env('QUEUE_SHELL'));
                dispatch($job);
            }
        })->name("delete_expired_jobs")->withoutOverlapping()->everyTenMinutes();
    }
    private function scheduleRatingGenerationJobs($schedule)
    {
        $schedule->call(function () {
            $organizations = Organization::where("status", 1)->get();
            foreach ($organizations as $organization) {
                $job = (new GenerateApplicantRatings($organization->user_id))->onQueue(env('QUEUE_SHELL'));
                dispatch($job);
            }
        })->name("generate_rating_jobs")->withoutOverlapping()->everyMinute();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}

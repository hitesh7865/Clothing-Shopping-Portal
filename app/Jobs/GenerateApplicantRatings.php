<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Enums\ApplicationStatus;
use App\Application;

class GenerateApplicantRatings implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $tries = 1;
    protected $orgId;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($orgId)
    {
        //
        $this->orgId = $orgId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        $applications = Application::where("org_id", $this->orgId)
                          ->where('status', ApplicationStatus::RESPONDED)
                          ->get();
        foreach ($applications as $application) {
            $application->generateRating();
        }
    }
}

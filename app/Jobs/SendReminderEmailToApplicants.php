<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use App\Services\MailService;
use App\Mailbox;
use App\Organization;

class SendReminderEmailToApplicants implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $tries = 3;
    protected $mailServices = [];
    
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($userId)
    {
        $organization = Organization::where('user_id', $userId)->first();
        $mailboxes = Mailbox::where('org_id', $organization->id)
                    ->where('status', 1)->get();
        
        foreach ($mailboxes as $mailbox) {
            array_push($this->mailServices, new MailService($userId, $mailbox));
            // array_push($this->mailboxes, $mailbox);
        }
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // $this->mailService->fetchEverything();
        foreach ($this->mailServices as $mailService) {
            $mailService->sendReminders();
        }
    }
    public function failed(Exception $exception)
    {
        // Log::error($exception->getMessage());
        // Bugsnag::notifyError('ErrorType', 'Job failed');
        // Send user notification of failure, etc...
    }
}
<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class JobStatus extends Mailable
{
    use Queueable, SerializesModels;
    
    /**
     * Create a new message instance.
     *
     * @return void
     */

     public $fullname;
     public $job_name;
     public $subject;
     public $job_status;
    public function __construct($subject,$data)
    {

      
        $this->fullname = $data['user_name'];
        $this->job_name = $data['job_name'];
        $this->subject = $subject;
        $this->job_status = $data['job_status'];

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.job-status')
        ->subject($this->subject);
    }
}

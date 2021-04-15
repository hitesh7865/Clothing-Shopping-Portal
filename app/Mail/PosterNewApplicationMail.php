<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PosterNewApplicationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    public $fullname;
    public $subject;
    public $org_id;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subject, $fullname,$org_id = null)
    {
        $this->fullname = $fullname;
        $this->subject = $subject;
        $this->org_id = $org_id;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if($this->org_id == '11') {
            return $this->view('emails.its_poster_new_application')->subject($this->subject);
        }
        return $this->view('emails.its_poster_new_application')->subject($this->subject);
    }
}

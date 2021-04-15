<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class JobApprove extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $title;
    public $content;
    public $excontent;
    public $btn;
    public $link;
    public function __construct()
    {
        $this->title = 'Your oppurtunity is live';
        $this->content = 'The moderator has approved your opportunity post - it’s now live! We will notify you via e-mail when there is a new applicant.';
        $this->excontent = "We hope you find a suitable candidate. Please <a href=support@its52.com>contact us</a> if you have any questions.";
        $this->btn = "SEE OPPORTUNITY";
        $this->link = "support@its52.com";
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.common_edm')->subject($this->subject);
    }
}

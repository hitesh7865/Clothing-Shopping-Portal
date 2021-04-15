<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserCreate extends Mailable
{
    use Queueable, SerializesModels;
    public $fullname;
    public $subject;
    public $token;
    public $url;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subject,$fullname,$token)
    {

        $this->fullname = $fullname;
        $this->subject = $subject;
        $this->token = $token;
       // $this->url =  "/password/reset/".$this->token;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.user_reset_password')
        ->subject($this->subject);
    }
}

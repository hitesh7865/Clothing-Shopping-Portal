<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class blockuser extends Mailable
{
    use Queueable, SerializesModels;
    public $name;
    public $subject;
    public $cmessage;
    public $emessage;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subject,$name)
    {
        $this->subject = $subject;
        $this->name = $name;
        $this->cmessage = 'We inform you that you are <b>Block</b> By our Shopping site. We will unblock you in next 30 days and give one more chance to shop in our shopping site.';
        $this->emessage = 'Thank You...';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from("16bca029@charusat.edu.in","Divisima Clothes Shop")->view('admin.user.mail.blockmail')->subject($this->subject);
    }
}

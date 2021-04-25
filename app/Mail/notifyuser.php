<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class notifyuser extends Mailable
{
    use Queueable, SerializesModels;
    public $uname;
    public $subject;
    public $umessage;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subject,$uname,$umessage)
    {
        $this->subject = $subject;
        $this->uname = $uname;
        $this->umessage = $umessage;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from("16bca029@charusat.edu.in","Divisima Clothes Shop")->view('admin.user.mail.notify')->subject($this->subject);
    }
}

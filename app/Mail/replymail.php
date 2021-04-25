<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class replymail extends Mailable
{
    use Queueable, SerializesModels;
    public $cname;
    public $subject;
    public $cmessage;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subject,$cname,$cmessage)
    {
        $this->subject = $subject;
        $this->cname = $cname;
        $this->cmessage = $cmessage;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('16bca029@charusat.edu.in','Divisima Clothes Shop')->view('admin.contact.mail.replymail')->subject($this->subject);
    }
}

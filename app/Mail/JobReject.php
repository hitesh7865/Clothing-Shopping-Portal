<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class JobReject extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $title;
    public $excontent;
    public $content;
    public function __construct()
    {
        $this->excontent = 'Please <a href=support@its52.com target=_blank style=font-family: Roboto-Regular; font-weight: normal;
        line-height: 1.6; font-size: 20px; color: #b88200; text-align: center; text-decoration: none;>contact us</a> if you have any questions.';
        $this->content = 'We’re sorry to tell you that your opportunity post has been rejected. This means that the moderator finds that your content unsuitable. You may try making changes and re-submit your opportunity post.';
        $this->title = 'Opportunity post rejected';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.common_edm')
        ->subject($this->subject);
    }
}

<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ApplicationReminder extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    public $config;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $config = $this->config;
        $this->withSwiftMessage(function ($message) use ($config) {
            if (isset($config['in_reply_to'])) {
                $message->getHeaders()->addTextHeader('In-Reply-To', $config['in_reply_to']);
                $message->getHeaders()->addTextHeader('References', $config['in_reply_to']);
            }
        });
        return $this->view('emails.raise_question')->subject($config['subject'])->replyTo($config['reply_to']);
    }
}

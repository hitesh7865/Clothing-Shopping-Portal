<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

// use App\Enums\MailBoxStatus;

class Mailbox extends Model
{
    //
    protected $table = "mailboxes";
    protected $fillable = [
        'org_id','imap_name','imap_host','imap_user','imap_password','imap_connection_type','imap_folder',
        'imap_connection_status','status'
    ];
    public function organization()
    {
        return $this->belongsTo('App\Organization', 'org_id');
    }
    public function categories()
    {
        return $this->belongsToMany('App\Category', 'category_mailbox', 'mailbox_id', 'cat_id')->withTimestamps();
        ;
    }
}

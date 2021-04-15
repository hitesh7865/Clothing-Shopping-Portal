<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = "settings";
    protected $fillable = [
        'org_id', 'send_thankyou_email','send_reminder_email_days','status'
    ];
    public function organization()
    {
        return $this->belongsTo('App\Organization', 'org_id');
    }
}

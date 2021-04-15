<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reminder extends Model
{
    protected $table = "reminders";
  
    protected $fillable = [
      'app_id'
    ];
    public function application()
    {
        return $this->belongsTo('App\Application', 'app_id');
    }
}

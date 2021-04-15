<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class job_reject_logs extends Model
{
    protected $fillable = [
        'reason','rejected_by','status','job_id'
    ];
}

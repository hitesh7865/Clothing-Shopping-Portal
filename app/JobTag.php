<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobTag extends Model
{
    protected $fillable = ['job_id','tag_id','status'];

    public function tag() {
        return $this->belongsTo('App\Tag','tag_id','id');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobLocation extends Model
{
    protected $fillable = ['job_id', 'address', 'city','city_id','country','country_code','status'];

    public function categories() {
        return $this->belongsTo('App\Category','job_id');
    }
}

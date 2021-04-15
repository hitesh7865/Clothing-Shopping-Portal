<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobPreferredLocation extends Model
{
    protected $fillable = ['job_id', 'city_id', 'city_name','country','status'];

    public function categories() {
        return $this->belongsTo('App\Category','job_id');
    }
    
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FunctionalArea extends Model
{
    protected $fillable = [
        'name','org_id','created_by','status'
    ];
}

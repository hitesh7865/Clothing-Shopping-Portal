<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ApplicationMeta extends Model
{
    protected $table = 'application_meta';
    protected $fillable = [
        'id','app_id','meta_key','meta_value','status','created_at','updated_at'
    ];
}

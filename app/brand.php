<?php

namespace App;
use DB;
use Illuminate\Database\Eloquent\Model;

class brand extends Model
{
    protected   $table = 'brand';
    protected   $fillable = ['Brand_name'];
}

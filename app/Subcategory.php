<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subcategory extends Model
{
    protected   $table = 'subcategory';
    protected   $fillable = ['SubCategory_name','Category_id'];
}
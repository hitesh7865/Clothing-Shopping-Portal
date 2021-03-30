<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class product extends Model
{
    protected   $table = 'product';
    protected   $fillable = ['category_id','subcategory_id','Product_name','Product_brand','price','Discription','Color','Size','stock','pdate','photo'];
}

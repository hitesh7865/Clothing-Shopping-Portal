<?php

namespace App;
use DB;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected   $table = 'cart';
    protected   $fillable = ['cart_id','qty','product_id','user_id'];  
}

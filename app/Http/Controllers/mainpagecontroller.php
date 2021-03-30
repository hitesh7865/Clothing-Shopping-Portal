<?php

namespace App\Http\Controllers;
use App\Subcategory;
use App\Cart;
use App\category;
use Illuminate\Support\Facades\DB;
use App\Product;
use App\orderdata;
use App\User;
use Illuminate\Http\Request;

class mainpagecontroller extends Controller
{
    public static function index()
    {
        $ctu['usercount'] = DB::table('user')->count();
        $ctp['productcount'] = DB::table('product')->count();
        $totalsale['totsale'] = DB::table('orderdata')->sum('total');
        $cto['ordercount'] = DB::table('orderdata')->count();
        $arr['orderdata'] = DB::table('orderdata')
            ->where('status','=','0')
            ->get();
        return view('admin.adminindex')->with($arr)->with($ctu)->with($ctp)->with($totalsale)->with($cto);
    }
}

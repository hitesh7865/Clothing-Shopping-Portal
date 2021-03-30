<?php

namespace App\Http\Controllers;
use App\color;
use App\brand;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class brandcolorcontroller extends Controller
{
    function index()
    {
        $arr['color'] = color::all(); 
        return view('/admin.color.viewcolor')->with($arr);
    }

    public function view()
    { 
        $arr['brand'] = brand::all(); 
        return view('/admin.brand.viewbrand')->with($arr);
    }

    public function click_edit_color($id)
    {
        $arr['color'] = DB::select('select * from color where id = ?',[$id]); 
        return view("/admin.color.updatecolor")->with($arr);
    }

    public function click_edit_brand($id)
    {
        $arr['brand'] = DB::select('select * from brand where id = ?',[$id]); 
        return view("/admin.brand.updatebrand")->with($arr);
    }

    public function click_delete_color($id)
    {
        DB::delete('delete from color where id=?',[$id]);
        return redirect('/view-color')->with('success','Category Deleted');
    }

    public function click_delete_brand($id)
    {
        DB::delete('delete from brand where id=?',[$id]);
        return redirect('/view-brand')->with('success','Category Deleted');
    }
    
    function upbrand(Request $req,$id)
    {
        $cname = $req->input('Cname');
        DB::update('update brand set Brand_name = ? where id = ?',[$cname,$id]);
        return redirect('/view-brand');
    }

    function upcolor(Request $req,$id)
    {
        $cname = $req->input('color');
        DB::update('update color set color = ? where id = ?',[$cname,$id]);
        return redirect('/view-color');
    }
}

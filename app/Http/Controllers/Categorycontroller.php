<?php

namespace App\Http\Controllers;
use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Categorycontroller extends Controller
{
    function index()
    {
        $arr['category'] = Category::all(); 
        return view('Admin.category.viewcategory')->with($arr);
    }

    public function ucategory($id)
    {
        $arr['category'] = DB::select('select * from category where id = ?',[$id]); 
        return view("/admin.category.updatecategory")->with($arr);
    }

    function upcategory(Request $req,$id)
    {
        $cname = $req->input('Cname');
        $disc = $req->input('Discription');
        DB::update('update category set Cname = ?,Discription = ? where id = ?',[$cname,$disc,$id]);
        return redirect('/view-category');
    }

    public function dcategory($id)
    {
        DB::delete('delete from category where id=?',[$id]);
        return redirect('/view-category')->with('success','Category Deleted');
    }
}

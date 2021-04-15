<?php

namespace App\Http\Controllers;
use App\Category;
use App\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Subcategorycontroller extends Controller
{
    function index()
    {
        $arr['category'] = Category::all(); 
        return view('/admin.subcategory.addsubcategory')->with($arr);
    }

    public function view(Category $Category)
    { 
        $subcategory = array();
        $arr1 = Subcategory::all()->toArray();

        foreach($arr1 as $key => $data){
            $subcategory[$key]['Category_id'] = Category::where('id',$data['Category_id'])->select('Cname')->value('Cname');
            $subcategory[$key]['SubCategory_name'] = $data['SubCategory_name'];
            $subcategory[$key]['Discription'] =$data['Discription'];
            $subcategory[$key]['id'] =$data['id'];
        }
        return view('/admin.subcategory.viewsubcategory',['subcategory'=>$subcategory]);
    }

    public function ucategory($id)
    {
        $arr1['category'] = DB::select('select * from category');
        $arr['subcategory'] = DB::select('select * from subcategory where id = ?',[$id]); 
        return view("/admin.subcategory.updatesubcategory")->with($arr)->with($arr1);
    }

    function upcategory(Request $req,$id)
    {
        $scname = $req->input('SubCategory_name');
        $disc = $req->input('Discription');
        $cid = $req->input('Category_id');
        DB::update('update subcategory set SubCategory_name = ?,Discription = ?,Category_id = ? where id = ?',[$scname,$disc,$cid,$id]);
        return redirect('/view-subcategory');
    }

    public function dcategory($id)
    {
        DB::delete('delete from subcategory where id=?',[$id]);
        return redirect('/view-subcategory')->with('success','Category Deleted');
    }
}

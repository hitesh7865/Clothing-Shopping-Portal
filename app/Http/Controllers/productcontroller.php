<?php

namespace App\Http\Controllers;
use App\Product;
use App\Category;
use App\Subcategory;
use App\color;
use App\brand;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Response;
use Image;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class productcontroller extends Controller
{
    function index()
    {
        $arr['category'] = Category::all();
        $arr1['Subcategory'] = Subcategory::all();
        $arr2['Color'] = color::all();
        $arr3['Brand'] = brand::all();
        return view('/admin.product.addproduct')->with($arr)->with($arr1)->with($arr2)->with($arr3);
    }

    public function view(Category $Category,Subcategory $Subcategory)
    {
        $product = array();
        $arr1 = Product::all()->toArray();

        foreach($arr1 as $key => $data){
            $product[$key]['category_id'] = Category::where('id',$data['category_id'])->select('Cname')->value('Cname');
            $product[$key]['subcategory_id'] = Subcategory::where('id',$data['subcategory_id'])->select('SubCategory_name')->value('SubCategory_name');
            $product[$key]['Product_name'] = $data['Product_name'];
            $product[$key]['Product_brand'] =$data['Product_brand'];
            $product[$key]['Price'] =$data['Price'];
            $product[$key]['Description'] =$data['Description'];
            $product[$key]['Color'] =$data['Color'];
            $product[$key]['Size'] =$data['Size'];
            $product[$key]['Stock'] =$data['Stock'];
            $product[$key]['Posting_date'] =$data['Posting_date'];
            $product[$key]['photo'] =$data['photo'];
            $product[$key]['id'] =$data['id'];
        }
        // echo "<pre>";
        // print_r($product);die;
        return view('/admin.product.viewproduct',['product'=>$product]);
    }

    public function uproduct($id)
    {
        $arr1['category'] = Category::all();
        $arr2['subcategory'] = Subcategory::all();
        $arr3['Color'] = color::all();
        $arr4['Brand'] = brand::all();
        $arr['product'] = DB::select('select * from product where id = ?',[$id]); 
        return view("/admin.product.updateproduct")->with($arr)->with($arr1)->with($arr2)->with($arr3)->with($arr4);
    }

    function upproduct(Request $req,$id)
    {
        $category_id =  $req->input('category_id');
        $subcategory_id =  $req->input('subcategory_id');
        $Product_name = $req->input('product_name');
        $Product_brand = $req->input('product_brand');
        $price = $req->input('price');
        $Discription = $req->input('description');
        $Color = $req->input('color');
        $Size = $req->input('size');
        $stock = $req->input('stock');
        $pdate = date('Y-m-d');
       
        if ($req->hasfile('photo'))
        {
            $file = $req->file('photo');
            $exte = $file->getClientOriginalExtension();
            $fname = time().'.'.$exte;
            $file->move('uploads/productimage/',$fname);
            $pt = $fname;
        }
        else
        {
            return $req;
            $pt = "";
        }
        
        DB::update('update Product set category_id=?,subcategory_id=?,Product_name=?,Product_brand=?,Price=?,Description=? ,Color=? ,Size=?,Stock=? ,Posting_date=?,photo=? where id=?',[$category_id,$subcategory_id,$Product_name,$Product_brand,$price, $Discription , $Color,$Size,$stock ,$pdate,$pt,$id]);
        return redirect('/view-product');
    }

    public function dproduct($id)
    {
        DB::delete('delete from product where id=?',[$id]);
        return redirect('/view-product')->with('success','Product Deleted');
    }
}

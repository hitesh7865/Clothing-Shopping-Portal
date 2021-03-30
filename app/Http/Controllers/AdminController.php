<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Input;
use App\Users;
use App\Admin;
use App\Category;
use App\Product;
use App\orderdata;
use App\orderitem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\UploadedFile;
use Image;
use PDF;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{
    function index()
    {
        return view('/admin.adminlogin');
    }

    function logs(Request $request)
    {
        
        $email = $request->input('email');
        $password = $request->input('password');
        $dt = DB::select('select id from admin where email=? and password=?' ,[$email,$password]);

        if(count($dt))
        {   
            $ctu['usercount'] = DB::table('user')->count();
            $ctp['productcount'] = DB::table('product')->count();
            $totalsale['totsale'] = DB::table('orderdata')->sum('total');
            $cto['ordercount'] = DB::table('orderdata')->count();

            $data['admin'] = DB::select('select username from admin where email=?',[$email]);
            $arr['orderdata'] = DB::table('orderdata')
                                ->where('status','=','0')
                                ->get();
            return view('/admin.adminindex')->with($arr)->with($ctu)->with($ctp)->with($totalsale)->with($cto);
        }
        else
        {
            return redirect('/Login');
        }
    }

    function add_category(Request $req)
    {
        $cname = $req->input('Cname');
        $disc = $req->input('Discription');

        $data = array('Cname'=>$cname,'Discription'=>$disc);
        $dt = DB::table('Category')->insert($data);

        return redirect('/Add-Category');
    }

    function add_subcategory(Request $req)
    {
        $scname = $req->input('SubCategory_name');
        $Discription = $req->input('Discription');
        $cid = $req->input('Category_id');

        $data = array('SubCategory_name'=>$scname,'Discription'=>$Discription,'Category_id'=>$cid);
        $dt = DB::table('subcategory')->insert($data);

        return redirect('/addsubcategory');
    }

    function addproduct(Request $req)
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
        
        $form_data = array('category_id'=>$category_id,'subcategory_id'=>$subcategory_id,'product_name'=>$Product_name,'product_brand'=>$Product_brand,'price'=>$price,'description'=> $Discription ,'color'=> $Color,'size'=>$Size,'stock'=>$stock ,'posting_date'=>$pdate,'photo'=>$pt);
        DB::table('Product')->insert($form_data);
        return redirect('/addproduct');
                
    }

    function add_brand(Request $req)
    {
        $cname = $req->input('brand_name');

        $data = array('brand_name'=>$cname);
        $dt = DB::table('brand')->insert($data);

        return redirect('/addbrand');
    }
    
    function add_color(Request $req)
    {
        $cname = $req->input('Colorname');

        $data = array('Color'=>$cname);
        $dt = DB::table('color')->insert($data);

        return redirect('/addcolor');
    }

    public function vieworder($id)
    {
        $arr['data'] = DB::table('orderitem')
            ->where('orderitem.order_id','=',$id)
            ->join('product','product.id','=','orderitem.product_id')
            ->select('orderitem.qty','orderitem.order_id','product.photo','product.Product_name','product.Price')
            ->get();

        return view('/admin.order.vieworder')->with($arr);
    }

    function updateorder($id)
    {
        $status=1;
        DB::update('update orderdata set status = ? where orderid = ?',[$status,$id]);
        
        $data = orderdata::where('orderid','=',$id)->first();
        
        $userid = $data->user_id;
        
        $userdetails = Users::where('id','=',$userid)->first();
        $name = $userdetails->Firstname;
        $email = $userdetails->email;

        $md = ['email'=>$email,'name'=>$name];

        Mail::send('User.dispatch',$md,function($message)use($email)
        {
            $message->to($email)->subject('Order Dispached...');
        });
        return redirect('/AdminPanel');
    }
    function logout()
    {
        return redirect('Login');
    }   
}
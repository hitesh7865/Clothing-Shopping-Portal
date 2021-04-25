<?php

namespace App\Http\Controllers;
use App\placeorder;
use App\Subcategory;
use App\Cart;
use App\category;
use App\Product;
use App\orderdata;
use App\orderitem;
use DB;
use App\Users;
use Session;
use Mail;
use Illuminate\Http\Request;

class Bookingcontroller extends Controller
{
    public function index(Request $request)
    {
        $userid = Session::get('userid');
        $data = DB::table('cart')
            ->where('cart.user_id','=',$userid)
            ->join('product','product.id','=','cart.product_id')
            ->join('user','user.id','=','cart.user_id')
            ->select('product.id','cart.cart_id','product.photo','product.Product_name','product.Price','cart.qty')
            ->get();
        
        $subtot=0;
        $tot=0;
        foreach($data as $d)
        {
            $subtot = $d->qty * $d->Price;
            $tot = $tot + $subtot;
        }

        $Address = $request->input('Address');
        $Zip = $request->input('Zip_code');
        $phone = $request->input('Phone_no');
        DB::update('update user set Address = ?,Zip_code = ?,mobile = ? where id = ?',[$Address,$Zip,$phone,$userid]);
        
        $status=0;
        $odata = array('total'=>$tot,'status'=>$status,'user_id'=>$userid);
        DB::table('orderdata')->insert($odata);

        $orderid = DB::table('orderdata')->max('orderid');
        foreach($data as $d)
        {
            $productid = $d->id;
            $productname = $d->Product_name;
            $qty = $d->qty;
            $oitem = array('qty'=>$qty,'Product_id'=>$productid,'order_id'=>$orderid);
            DB::table('orderitem')->insert($oitem);
        }
        DB::delete('delete from cart where user_id=?',[$userid]);
        
        $billdata = DB::table('orderdata')
            ->where('orderdata.user_id','=',$userid)
            ->where('orderdata.status','=','0')
            ->join('orderitem','orderitem.order_id','=','orderdata.orderid')
            ->join('product','product.id','=','orderitem.product_id')
            ->select('orderitem.qty','product.photo','product.Product_name','product.Price')
            ->get();

            $userdetails = Users::where('id','=',$userid)->first();

            $name = $userdetails->Firstname;
            $email = $userdetails->email;
            
            $md = ['email'=>$email,'billdata'=>$billdata,'ud'=>$name];
            // echo '<pre>';
            // print_r($md);die;
            Mail::send('User.Booking',$md,function($message)use($email)
            {
                $message->to($email)->subject('Order Confirmation...');   
            });
        return redirect('/user');
    }
}
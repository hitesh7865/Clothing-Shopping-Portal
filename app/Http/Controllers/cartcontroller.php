<?php

namespace App\Http\Controllers;
use App\Subcategory;
use App\Cart;
use App\category;
use Illuminate\Support\Facades\DB;
use App\Product;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

class cartcontroller extends Controller
{
    public function index($id)
    {
        $userid = Session::get('userid');
        $ct = DB::table('cart')
                ->where('cart.user_id','=',$userid)
                ->join('product','product.id','=','cart.product_id')
                ->join('user','user.id','=','cart.user_id')
                ->select('cart.cart_id','product.photo','product.Product_name','product.Price','cart.qty')
                ->count();

        $product = DB::select('select * from product where id=?',[$id]);
        return view('User.product',['product'=>$product,'ct'=>$ct]);
    }

    public function addcart(Request $req,$id)
    {
       
        
        $qty = $req->input('qty');
        $productid = $id;
        $userid = Session::get('userid');

        $arr = Cart::all();
        $ct=0;

        foreach($arr as $a)
        {
            if($a->product_id==$id && $a->user_id=$userid)
            {
                $ct=$ct+1;
            }
        }

        if($ct>1)
        {
            $data = DB::table('cart')
                ->where('cart.user_id','=',$userid)
                ->join('product','product.id','=','cart.product_id')
                ->join('user','user.id','=','cart.user_id')
                ->select('cart.cart_id','product.photo','product.Product_name','product.Price','cart.qty')
                ->get();
            
            $ct = DB::table('cart')
                ->where('cart.user_id','=',$userid)
                ->join('product','product.id','=','cart.product_id')
                ->join('user','user.id','=','cart.user_id')
                ->select('cart.cart_id','product.photo','product.Product_name','product.Price','cart.qty')
                ->count();

                $subtot=0;
                $tot=0;
                foreach($data as $d)
                {
                    $subtot = $d->qty * $d->Price;
                    $tot = $tot + $subtot;
                }
        
                return view('User.cart',compact('data','tot','ct'));
        }
        else
        {
            $cdata = array('qty'=>$qty,'product_id'=>$productid,'user_id'=>$userid);
            $dt = DB::table('cart')->insert($cdata);

            $data = DB::table('cart')
                ->where('cart.user_id','=',$userid)
                ->join('product','product.id','=','cart.product_id')
                ->join('user','user.id','=','cart.user_id')
                ->select('cart.cart_id','product.photo','product.Product_name','product.Price','cart.qty')
                ->get();
            
            $ct = DB::table('cart')
                ->where('cart.user_id','=',$userid)
                ->join('product','product.id','=','cart.product_id')
                ->join('user','user.id','=','cart.user_id')
                ->select('cart.cart_id','product.photo','product.Product_name','product.Price','cart.qty')
                ->count();

                $subtot=0;
                $tot=0;
                foreach($data as $d)
                {
                    $subtot = $d->qty * $d->Price;
                    $tot = $tot + $subtot;
                }
                return view('User.cart',compact('data','tot','ct'));
        }
    }

    function shoppingcart()
    {
        $userid = Session::get('userid');
        if($userid==Null)
        {
            return redirect('/user-login');
        }
        else
        {
            $data = DB::table('cart')
            ->where('cart.user_id','=',$userid)
            ->join('product','product.id','=','cart.product_id')
            ->join('user','user.id','=','cart.user_id')
            ->select('cart.cart_id','product.photo','product.Product_name','product.Price','cart.qty')
            ->get();

            $ct = DB::table('cart')
            ->where('cart.user_id','=',$userid)
            ->join('product','product.id','=','cart.product_id')
            ->join('user','user.id','=','cart.user_id')
            ->select('cart.cart_id','product.photo','product.Product_name','product.Price','cart.qty')
            ->count();

            $subtot=0;
            $tot=0;
            foreach($data as $d)
            {
                $subtot = $d->qty * $d->Price;
                $tot = $tot + $subtot;
            }
            return view('User.cart',compact('data','tot','ct'));
        }
        
    }


    public function deletecart($id)
    {
        DB::delete('delete from cart where cart_id=?',[$id]);

        $userid = Session::get('userid');
        $data = DB::table('cart')
        ->where('cart.user_id','=',$userid)
        ->join('product','product.id','=','cart.product_id')
        ->join('user','user.id','=','cart.user_id')
        ->select('cart.cart_id','product.photo','product.Product_name','product.Price','cart.qty')
        ->get();

        $ct = DB::table('cart')
        ->where('cart.user_id','=',$userid)
        ->join('product','product.id','=','cart.product_id')
        ->join('user','user.id','=','cart.user_id')
        ->select('cart.cart_id','product.photo','product.Product_name','product.Price','cart.qty')
        ->count();

        $subtot=0;
        $tot=0;
        foreach($data as $d)
        {
            $subtot = $d->qty * $d->Price;
            $tot = $tot + $subtot;
        }

        return view('User.cart',compact('data','tot','ct'));

    }

    public function checkoutdata()
    {
        $userid = Session::get('userid');
        $data = DB::table('cart')
        ->where('cart.user_id','=',$userid)
        ->join('product','product.id','=','cart.product_id')
        ->join('user','user.id','=','cart.user_id')
        ->select('cart.cart_id','product.photo','product.Product_name','product.Price','cart.qty')
        ->get();
        
        $ct = DB::table('cart')
        ->where('cart.user_id','=',$userid)
        ->join('product','product.id','=','cart.product_id')
        ->join('user','user.id','=','cart.user_id')
        ->select('cart.cart_id','product.photo','product.Product_name','product.Price','cart.qty')
        ->count();

        if($ct==0)
        {
            return redirect('/user');
        }
        else
        {
            $subtot=0;
            $tot=0;
            foreach($data as $d)
            {
                $subtot = $d->qty * $d->Price;
                $tot = $tot + $subtot;
            }
            return view('User.checkout',compact('data','tot','ct'));
        }
    }
}
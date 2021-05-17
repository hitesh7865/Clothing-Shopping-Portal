<?php

namespace App\Http\Controllers;
use App\Subcategory;
use App\Cart;
use App\category;
use Illuminate\Support\Facades\DB;
use App\Product;
use App\orderdata;
use App\wishlist;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

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

    function addwishlist($id)
    {
        $userid = Session::get('userid');
        if($userid==Null)
        {
            return redirect('/user-login');
        }
        else
        {
            $data = wishlist::where('product_id',$id)->where('user_id',$userid)->get();

            if($data == NULL)
            { 
                $ct['ct'] = DB::table('cart')
                ->where('cart.user_id','=',$userid)
                ->join('product','product.id','=','cart.product_id')
                ->join('user','user.id','=','cart.user_id')
                ->select('cart.cart_id','product.photo','product.Product_name','product.Price','cart.qty')
                ->count();

                $arr['product'] = Product::all();
                return view('/user.index')->with($arr)->with($ct);
            }
            else
            {
                $wish = new wishlist;
                $wish->product_id = $id;
                $wish->user_id = $userid;
                $wish->save();
            }
            $ct['ct'] = DB::table('cart')
                ->where('cart.user_id','=',$userid)
                ->join('product','product.id','=','cart.product_id')
                ->join('user','user.id','=','cart.user_id')
                ->select('cart.cart_id','product.photo','product.Product_name','product.Price','cart.qty')
                ->count();

            $arr['product'] = Product::all();
            return view('/user.index')->with($arr)->with($ct);
        }
    }

    function addtocart(Request $req,$id)
    {
        $qty = $req->input('qty');
        $wid = $req->input('wid');
        $productid = $id;
        
        $userid = Session::get('userid');
        
        if($userid==Null)
        {
            return redirect('/user-login');
        }
        else
        {
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
                    wishlist::where('id',$wid)->delete();
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
                    wishlist::where('id',$wid)->delete();
                    return view('/User.cart',compact('data','tot','ct'));
            }
        }
    }

    function viewwishlist()
    {
        $userid = Session::get('userid');
        if($userid==Null)
        {
            return redirect('/user-login');
        }
        else
        {
            $data = DB::table('wishlists')
            ->where('wishlists.user_id','=',$userid)
            ->join('product','product.id','=','wishlists.product_id')
            ->join('user','user.id','=','wishlists.user_id')
            ->select('wishlists.id','wishlists.product_id','product.photo','product.Product_name','product.Price')
            ->get();

            $ct = DB::table('cart')
            ->where('cart.user_id','=',$userid)
            ->join('product','product.id','=','cart.product_id')
            ->join('user','user.id','=','cart.user_id')
            ->select('cart.cart_id','product.photo','product.Product_name','product.Price','cart.qty')
            ->count();
            // print_r($data);die;
            return view('/User.wishlist',compact('data','ct'));
        }
    }

    function deletewishlist($id)
    {   
        $userid = Session::get('userid');

        wishlist::where('id',$id)->delete();
        
        return back();
    }

    function searchbox(Request $req)
    {
        $searchbar = $req->input('searchbar');
        $category = $req->input('category');
        $userid = Session::get('userid');

        $color = product::where('Color',$searchbar)->get();
        $brand = product::where('Product_brand',$searchbar)->get();
        $data['product'] = [];
        if($category == 'Color')
        {
            $data['product'] = $color;
        }
        else if($category == 'Brand')
        {
            $data['product'] = $brand;
        }

        $ct['ct'] = DB::table('cart')
            ->where('cart.user_id','=',$userid)
            ->join('product','product.id','=','cart.product_id')
            ->join('user','user.id','=','cart.user_id')
            ->select('cart.cart_id','product.photo','product.Product_name','product.Price','cart.qty')
            ->count();
        
        return view('/user.index')->with($data)->with($ct);
    }
}

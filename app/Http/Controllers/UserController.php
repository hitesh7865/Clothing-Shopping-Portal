<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use App\Users;
use App\Product;
use App\Category;
use App\Subcategory;

use Illuminate\Http\Request;
use App\Http\Controllers\str_rendom;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use App\Mail\blockuser;
use App\Mail\unblockuser;
use App\Mail\notifyuser;

class UserController extends Controller
{
    function index()
    {  
        if(Session::has('username'))
        {
            $userid = Session::get('userid');
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
            $ct['ct'] = 0;
            $arr['product'] = Product::all();
            return view('/user.index')->with($arr)->with($ct); 
        }
    }

    function Rdata(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => ['required','string','max:150'],
            'lastname' => ['required','string','max:150'],
            'username' => ['required','string','max:150'],
            'email' => ['required','email','unique:user'],
            'password' => ['required','string','max:150']
        ]);

        $fname = $request->input('firstname');
        $lname = $request->input('lastname');
        $username = $request->input('username');
        $email = $request->input('email');
        $password = $request->input('password');
        $address = $request->input('Address');
        $zipcode = $request->input('Zip_code');
        $mobile = $request->input('mobile');
        $status = 1;

        $data = array('firstname'=>$fname,'lastname'=>$lname,'username'=>$username,'email'=>$email,'password'=>$password,'Address'=>$address,'Zip_code'=>$zipcode,'mobile'=>$mobile,'status'=>$status);
        $dt = DB::table('user')->insert($data);

        if($dt>0)
        {
            $ct['ct'] = 0;
            $arr['product'] = Product::all();
            $md = ['email'=>$email,'name'=>$fname];
            Mail::send('User.registermail',$md,function($message)use($email)
            {
                $message->to($email)->subject('Register Succesfully');
                
            });
            return view('/user.index')->with($arr)->with($ct);   
        }
        else
        {
            return redirect('/user-register');
        }
    }

    function logins(Request $request)
    {
        if(Session::has('username'))
        {
            $userid = Session::get('userid');
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
            $email = $request->input('username');
            $password = $request->input('password');
            $dt = DB::select('select id from user where email=? and password=?' ,[$email,$password]); 
            
            if(Users::where('email',$email)->select('status')->value('status')==1)
            {
                if(count($dt)>0)
                {
                    $data= Users::where('email',$email)->where('password',$password)->get();
                    foreach($data as $d)
                    {

                    }
                    $request->session()->put('username',$d->Firstname);
                    $request->session()->put('userid',$d->id);
                    $userid = Session::get('userid');        
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
                    return redirect('/user-login');
                }
            }
            else
            {
                return redirect('/user-login');
            }
        }
    }

    public function forget(Request $req)
    {
        if($req->isMethod('post'))
        {
            $data1 = $req->all();
            $usercount = Users::where('email',$data1['email'])->count();
            if($usercount == 0)
            {
                return redirect('/forgetpassword')->with('error');
            }

            $userdetails = Users::where('email',$data1['email'])->first();

            $rp = str_random(8);
            $np = $rp; 

            Users::where('email',$data1['email'])->update(['password'=>$np]);

            $email = $data1['email'];
            $name = $userdetails->Firstname;
            $md = ['email'=>$email,'name'=>$name,'password'=>$np];

            Mail::send('User.letterforget',$md,function($message)use($email)
            {
                $message->to($email)->subject('Forget Password');
                
            });
            return redirect('/resetpage');

        }
        return redirect('/forgetpassword');
    }

    function resetpassword(Request $req)
    {
        $email = $req->input('email');
        $mpass = $req->input('Confirm');
        $password = $req->input('password');

        $usercount = Users::where('email',$email)
                            ->where('password',$mpass)
                            ->count();
        if($usercount>0);
        {
            DB::update("update user set password=? where email=?",[$password,$email]);
            return redirect('/user-login');
        }
    }

    function usermail($id)
    {
        $email = Users::where('id',$id)->select('email')->value('email');
        Mail::to($email)->send((new notifyuser('User Notify Successfully..',)));
        return back();
    }

    function userblock($id)
    {
        $email = Users::where('id',$id)->select('email')->value('email');
        if(Users::where('id',$id)->select('status')->value('status')==0)
        {
            $status=1;
            $user = DB::update('update user set status=? where id=?',[$status,$id]);
            Mail::to($email)->send((new unblockuser('User Unblock Successfully..',)));
            return back();
        }
        else
        {
            $status=0;
            $user = DB::update('update user set status=? where id=?',[$status,$id]);
            Mail::to($email)->send((new blockuser('User Block Successfully..',)));
            return back();
        }
        
    }

    function addcontact(Request $request)
    {
        $name =  $request->input('name');
        $email =  $request->input('email');
        $subject = $request->input('subject');
        $message = $request->input('message');

        $contact_data=array('name'=>$name,'email'=>$email,'subject'=>$subject,'message'=>$message);
        DB::table('contactus')->insert($contact_data);
        return back();
        
    }
    function userlogout(Request $req)
    {
        Session::flush();
        return redirect('/user');
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\LoginRequest;
use DB;

class LoginController extends Controller
{
 
    public function index()
    {
        return response()->view('login');
    }
    public function authenticate(LoginRequest $request)
    {

        
        $email = $request->input('email');
        $password = $request->input('password');
        
        if (Auth::attempt(['email' => $email, 'password' => $password])) {
          //  die('222');
            $role_id = User::select('role_id')->where('email',$email)->first();
          
            // Authentication passed...

            return redirect()->intended('dashboard');
        
        } else {
            $request->session()->flash('error', 'A user with given email and password could not be found. Please try again.');
            return back()->withInput();
        }
    }
    
    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
}

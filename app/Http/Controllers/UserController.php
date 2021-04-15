<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Organization;
use App\Setting;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $organization = Organization::where('user_id', Auth::user()->id)->first();

        return response()->view('partials.settings.profile', ['data' => Auth::user()]);
    }
    public function update(User $user, Request $request)
    {
        
        if (Auth::user()->id != $user->id) {
            abort(401, 'Unauthorized.');
        }

        $validator = Validator::make(
            $request->all(),
            [
                'fullname' => 'required|string|max:255',
                'user_name' => 'string|max:30|unique:users,user_name,' .Auth::user()->id ,
                'email' => 'required|string|email|max:255|unique:users,email,'. Auth::user()->id,
                // 'password' => 'required|string|min:3'
            ]
        );
        if ($validator->fails()) {
            $error = $validator->errors();
            return redirect()->back()->with('errors', $error)->withInput();
        }

        $currentUser = Auth::user();

        $requestData = $request->all();
        if (!empty($request['new_password'])) {
            $requestData['password'] = Hash::make($request['new_password']);
        }
        $currentUser->update($requestData);
        $request->session()->flash('success', 'Profile has been updated');
        return back()->withInput();
    }
}

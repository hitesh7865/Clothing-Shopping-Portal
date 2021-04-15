<?php

namespace App\Http\Controllers\Organization;

use App\User;
use App\Organization;
use Illuminate\Http\Request;
use App\Tag;
use App\Category;
use Datatables;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Mail;
use Carbon\Carbon;
use DB;
use App\Mail\UserCreate;
use Illuminate\Support\Facades\Password;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * 
     */

    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        return view('user.list');
    }

    public function getUsers()
    {

        $organization = Organization::where('user_id', Auth::user()->id)->first();
        $users = User::where('organization_id', Auth::user()->organization_id)
            ->whereIn('status', [1, 0])
            ->whereNotIn('role_id', [1])
            ->orderBy('updated_at', 'desc');

        return DataTables::of($users)
            ->addColumn('status', function ($users) {
                return ($users->status == '1') ? '<div class="status status_active">Active</div>' : '<div class="status status_paused">In Active</div>';
            })
            ->addColumn('action', function ($users) {
                return '<a href="/organization/users/' . $users->id . '/edit' . '" class="btn btn_xs btn_blue">Edit</a>

                <form id="delete-user' . $users->id . '" action="' . route('users.destroy', $users->id) . '" method="POST" class="">
                   <input type="hidden" name="_token" value="' . csrf_token() . '">
                   <input type="hidden" name="_method" value="delete" />
                   <button type="submit" class="btn btn_xs btn_red">
                    Delete
                    </button>
                </form>
';
            })
            ->rawColumns(['action', 'status'])
            ->make(true);
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('user.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|string|max:255',
                'user_name' => 'string|max:30|unique:users',
                'email' => 'required|string|email|max:255|unique:users',
                // 'password' => 'required|string|min:3'
            ]
        );

        // echo "<pre>";
        // print_r($request->all());
        // die;
        if ($validator->fails()) {
            $error = $validator->errors();
            return redirect()->back()->with('errors', $error)->withInput();
        }
        $organization = Organization::where("user_id", Auth::user()->id)->first();
        $user = User::create([
            "fullname" => $request->name,
            "user_name" => $request->user_name,
            "email" => $request->email,
            "phone" => $request->phone,
            "password" => bcrypt($request->password),
            "organization_id" => Auth::user()->organization_id,
            "role_id" => $request->role
        ]);
        if ($user) {
            $reset_password = DB::table('password_resets')->insert([
                'email' => $request->email,
                'token' => str_random(60),
                'created_at' => Carbon::now()
            ]);

            //Get the token just created above
            // $tokenData = DB::table('password_resets')
            // ->where('email', $request->email)->first();
            $credentials = ['email' => $request->email];
            $response = Password::sendResetLink($credentials, function (Message $message) {
                $message->subject($this->getEmailSubject());
            });


            $request->session()->flash('success', 'User Is Created!');
            return redirect(route('users.index'));
            // Mail::to($request->email)->send((new UserCreate('User Created',$request->name,$tokenData->token)));
        } else {
            return redirect()->back()->withInput();
        }
        //  $request->session()->flash('success', 'User Created Successfully');

    }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        // $organization = Organization::where('user_id', Auth::user()->id)->first();
        // Get Questions
        $user = User::find($id)->toArray();

        return view('user.edit', ['data' => $user]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|string|max:255',
                'user_name' => 'string|max:30|unique:users,user_name,' .$id ,
                'email' => 'required|string|email|max:255|unique:users,email,'. $id,
                // 'password' => 'required|string|min:3'
            ]
        );
        if ($validator->fails()) {
            $error = $validator->errors();
            return redirect()->back()->with('errors', $error)->withInput();
        }

        $user = User::where('id', $id)->first();
        $user->update($request->all());
        if (isset($request->status)) {

            User::where('id', $id)->update(['status' => 1]);
        } else {
            User::where('id', $id)->update(['status' => 0]);
        }
        $request->session()->flash('success', 'User has been updated!');
        return redirect(route('users.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $status = config('enums.STATUS');
        $job_status = config('enums.JOB_STATUS');
        $user = User::where('id', $id)->update(['status' => $status['DEL']]);
        if ($user) {
            Category::where('created_by', $id)->update(['status' => $job_status['DEL']]);
            $request->session()->flash('success', 'User Is Deleted!');
            return redirect(route('users.index'));
        }
    }
}

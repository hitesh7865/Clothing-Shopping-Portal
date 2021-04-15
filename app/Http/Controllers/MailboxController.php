<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UpdateMailBoxRequest;
use App\Services\MailService;
use App\Users;
use App\Organization;
use App\Mailbox;
use Datatables;
use Crypt;
use App\Enums\ConnectionTypes;

class MailboxController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('mailbox_list');
    }
    public function getMailboxes(Request $request)
    {
        $organization = Organization::where('user_id', Auth::user()->id)->first();
        $mailboxes = Mailbox::where('org_id', Auth::user()->organization_id)->whereNotIn('status',[5])->orderBy('updated_at', 'desc');
        return DataTables::of($mailboxes)
          ->addColumn('action', function ($mailbox) {
              return '<a href="' . route('mailboxes.edit', $mailbox->id).'" class="btn btn_xs btn_blue">Edit</a>
              <form id="delete-mail' . $mailbox->id . '" action="' . route('mailboxes.destroy', $mailbox->id) . '" method="POST" class="">
                   <input type="hidden" name="_token" value="' . csrf_token() . '">
                   <input type="hidden" name="_method" value="delete" />
                   <button type="submit" class="btn btn_xs btn_red">
                    Delete
                    </button>
                </form>
                ';
          })
          ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return response()->view('mailbox', ['connectionTypes' => ConnectionTypes::LIST_ALL]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $organization = Organization::where('user_id', Auth::user()->id)->first();
        $requestData = $request->all();
        $requestData['org_id'] = Auth::user()->organization_id;
        $requestData['status'] = (isset($requestData['status']) && $requestData['status'] == "on") ? 1 : 0;
        if (isset($requestData['imap_password'])) {
            $requestData['imap_password'] =  Crypt::encrypt($requestData['imap_password']);
        }
        Mailbox::create($requestData);
        $request->session()->flash('success', 'Details have been saved! Chillax.');
        return redirect('mailboxes');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\MailBox  $mailbox
     * @return \Illuminate\Http\Response
     */
    public function show(MailBox $mailbox)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\MailBox  $mailbox
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Mailbox $mailbox)
    {
        $organization = Organization::where('user_id', Auth::user()->id)->first();
        $data = $mailbox->toArray();
        if (!empty($data['imap_password'])) {
            $data['imap_password'] = Crypt::decrypt($data['imap_password']);
        }
        
        if (Auth::user()->organization_id == $mailbox->org_id) {
            return response()->view('mailbox', ['data' => $data,'connectionTypes' => ConnectionTypes::LIST_ALL]);
        } else {
            // unauthorized
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\MailBox  $mailbox
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateMailBoxRequest $request, MailBox $mailbox)
    {
        $requestData = $request->all();
        $requestData['status'] = (isset($requestData['status']) && $requestData['status'] == "on") ? 1 : 0;
        if (isset($requestData['imap_password'])) {
            $requestData['imap_password'] =  Crypt::encrypt($requestData['imap_password']);
        }
        $mailbox->update($requestData);
        $request->session()->flash('success', 'Details have been saved! Chillax.');
        return redirect('/mailboxes');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\MailBox  $mailbox
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        $status = config('enums.STATUS');
        $jobs = Mailbox::where('id',$id)->update(['status' => $status['DEL']]);

        if($jobs)
        {
            $request->session()->flash('success', 'Mail Box has been deleted!');
            return redirect('/mailboxes');
        }
        else
        {
            $request->session()->flash('error', 'There Is Some Issue!');
            return redirect('/mailboxes');
        }       
    }
    public function testImap(Request $request)
    {
        $status = MailService::testConnection($request->all());
        return response()->json($status);
    }
}

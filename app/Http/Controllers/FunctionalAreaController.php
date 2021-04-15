<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Organization;
use App\Category;
use App\Mailbox;
use App\Question;
use App\FunctionalArea;
use Bugsnag;
use Datatables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
class FunctionalAreaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('functional-area.list');
        
    }

    public function getfunctionalarea()
    {
        $organization = Organization::where('user_id', Auth::user()->id)->first();

        $functional_areas = FunctionalArea::where('org_id', Auth::user()->organization_id)->whereNotIn('status',[5])->orderBy('updated_at', 'desc');
        return DataTables::of($functional_areas)
            ->addColumn('action', function ($functional_area) {
            return '<a href="/functional-areas/' . $functional_area->id. '/edit' . '" class="btn btn_xs btn_blue">Edit</a>

                <form id="delete-functional-area' . $functional_area->id . '" action="' . route('functional-areas.destroy', $functional_area->id) . '" method="POST" class="">
                   <input type="hidden" name="_token" value="' . csrf_token() . '">
                   <input type="hidden" name="_method" value="delete" />
                   <button type="submit" class="btn btn_xs btn_red">
                    Delete
                    </button>
                </form>
';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('functional-area.create');
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
        $org_id = Auth::user()->organization_id;
        $validator = Validator::make($request->all(), [
            'name' => [
                'required', 'string', 'max:250',
                Rule::unique('functional_areas')->where(function ($query) use ($org_id) {
                    return $query->where('status', '1')->where('org_id', '=', $org_id);
                })
            ],
        ]);

        if ($validator->fails()) {
            $error = $validator->errors();
            return redirect()->back()->with('errors', $error);
        }

        $requestData = $request->all();
        $requestData['org_id'] = Auth::user()->organization_id;
        $requestData['created_by'] = Auth::user()->id;
        $requestData['status'] = 1;

        $functional_area = FunctionalArea::create($requestData);

        $request->session()->flash('success', 'Details have been saved! Chillax.');
        return redirect('functional-areas');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $organization = Organization::where('user_id', Auth::user()->id)->first();
        // Get Questions
        $functional_area = FunctionalArea::find($id)->toArray();
        return response()->view('functional-area.create', ['data' => $functional_area]);
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
        $org_id = Auth::user()->organization_id;
        $validator = Validator::make($request->all(), [
            'name' => [
                'required', 'string', 'max:250',
                Rule::unique('functional_areas')->where(function ($query) use ($org_id,$id) {
                    return $query->where('status', '1')->where('org_id', '=', $org_id)->where('id','!=',$id);
                })
            ],
        ]);

        if ($validator->fails()) {
            $error = $validator->errors();
            return redirect()->back()->with('errors', $error);
        }

        $functional_area = FunctionalArea::where('id', $id)->first();
        $requestData = $request->all();
        $functional_area->update($requestData);
        $request->session()->flash('success', 'Functional area has been updated!');
        return redirect('/functional-areas');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        
        $status = config('enums.STATUS');
        $job = FunctionalArea::where('id',$id)->update(['status' => $status['DEL']]);
        if($job)
        {
            $request->session()->flash('success', 'Functional Area has been deleted!');
            return redirect('/functional-areas');
        }
        else{
            $request->session()->flash('error', 'There Is Some Issue!');
            return redirect('/functional-areas');
        }
        
    }
}

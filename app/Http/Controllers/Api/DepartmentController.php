<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\FunctionalArea;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $org_id = $request->instance()->query('org_id');
        $response = array('functional_areas' => array());
        $response['functional_areas'] = FunctionalArea::where('status', '=', '1')
            ->where('org_id', '=', $org_id)
            ->get();
        return $this->sendApiResponse($response);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:250'],
            // 'org_id' => ['required'],
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return $this->sendApiError($error, null, 422);
        }
        $org_id = $request->org_id;
        $functionalArea = new FunctionalArea();
        $functionalArea->name = $request->name;
        $functionalArea->org_id = $org_id;
        $functionalArea->status = '1';
        $functionalArea->created_by = '';
        $functionalArea->save();

        return $this->sendApiResponse($functionalArea, 'Question Set created successfully.', 200);
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
        //
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
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:250'],
            // 'org_id' => ['required'],
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return $this->sendApiError($error, null, 422);
        }
        $org_id = $request->org_id;
        $functionalArea = FunctionalArea::find($id);
        $functionalArea->name = $request->name;
        $functionalArea->created_by = '';
        $functionalArea->save();

        return $this->sendApiResponse($functionalArea, 'Question Set updated successfully.', 200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        $functionalArea = FunctionalArea::find($id);
        $functionalArea->status = '2';
        $functionalArea->save();

        return $this->sendApiResponse($functionalArea, 'Question Set deleted successfully.', 200);
    }
}

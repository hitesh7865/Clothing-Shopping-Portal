<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
class MigrationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function changeUserDetail(Request $request)
    {
        $users = User::with(['organizations'])->where('organization_id',NULL)->get()->toArray();

        foreach($users as $user) {
            $dbUser = User::find($user['id']);
            $dbUser->organization_id = $user['organizations'][0]['id'];
            $dbUser->save();
        }
        return $this->sendApiResponse(array(),'done');
    }

}

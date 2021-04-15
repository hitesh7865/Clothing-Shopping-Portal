<?php

namespace App\Http\Middleware;
use Crypt;
use Closure;
use App\Organization;
class verifyApiKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = [
            'status'    => array(
                'code' => '401',
                'error' => true,
                'message'   => 'Unauthorized'
            ),
        ];
        $bearerToken = '';
        $bearerToken =  $request->bearerToken();
        
        if($bearerToken == '') {
            return response($response,401);
        }
        $organization = array();
        $organization = Organization::where('api_key' ,'=', $request->bearerToken())->first(); // added toArray to solve the countable error for $organization

        
        if(count($organization) > 0){
            $organization = $organization->toArray();
        }
        
        if(count($organization) == 0) {
            return response($response,401);
        } 
        $request->merge(array("org_id" => $organization['id']));
        return $next($request);
    }
}

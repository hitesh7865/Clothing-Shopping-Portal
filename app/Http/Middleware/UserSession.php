<?php

namespace App\Http\Middleware;

use Closure;

class UserSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        if($role == "admin") {
            if(!$request->session()->has('admin')) {
                return redirect('/admin');
            }
        }
        return $next($request);
    }
}

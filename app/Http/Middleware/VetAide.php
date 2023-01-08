<?php

namespace App\Http\Middleware;

use App\Models\UserRoles;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class VetAide
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string $role)
    {
        if(Auth::check()){
            $data = UserRoles::where('user_id',auth()->user()->id)->get();
            $role_arr = [];

            foreach ($data as $key) {
                array_push($role_arr, $key->role);
            }

            if($role == 'VetAide'){
                if(in_array("VetAide", $role_arr) || in_array("Admin", $role_arr)){
                    return $next($request);
                }
                return Redirect::route('checkRole')->with('error','Restricted Access!');
            }
        }else{
            // Redirect to login
            return Redirect::route('login');
        }
        return $next($request);
    }
}

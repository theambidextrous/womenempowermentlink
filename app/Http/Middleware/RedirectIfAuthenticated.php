<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            if( Auth::user()->is_admin ){
                return redirect()->route('a_home');
            }
            
            if( Auth::user()->is_teacher ){
                return redirect()->route('t_home');
            }
            
            if( Auth::user()->is_student ){
                return redirect()->route('s_home');
            }
            return redirect()->route('welcome');
            // return redirect(RouteServiceProvider::HOME);
        }

        return $next($request);
    }
}

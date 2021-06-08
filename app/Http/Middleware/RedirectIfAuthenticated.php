<?php

namespace App\Http\Middleware;

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
			
			$user = auth()->user();
			$role = $user->roles->first()->name;
			if($role == 'client'){
				return redirect('/client/home');
			}else{
				return redirect('/admin/home');
			}

        }

        return $next($request);
    }
}

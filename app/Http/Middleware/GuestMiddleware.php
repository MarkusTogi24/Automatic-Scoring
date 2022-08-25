<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuestMiddleware
{
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                if(Auth::user()->role == "ADMIN") {
                    return redirect()->intended('/admin/dashboard');
                }else{
                    return redirect()->intended('/beranda');
                }
            }
        }
        return $next($request);
    }
}
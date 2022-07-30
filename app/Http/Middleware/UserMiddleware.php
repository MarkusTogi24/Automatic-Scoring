<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class UserMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();
        if ($user->role == "ADMIN") {
            return response()->view('welcome');
        }
        return $next($request);
    }
}

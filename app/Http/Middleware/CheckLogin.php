<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckLogin
{
    public function handle(Request $request, Closure $next)
    {
        if (!backpack_auth()->check()) {
            return redirect()->route('http://127.0.0.1:8000/admin/post');
        }

        return $next($request);
    }
}

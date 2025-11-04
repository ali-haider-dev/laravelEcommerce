<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // User must be logged in
        if (!Auth::check()) {
            return redirect()->route('admin.login');
        }

        // Allow only admin designation
        if (strtolower(Auth::user()->designation) !== 'admin') {
            return redirect()->route('user');
        }

        // Admin? Good to go!
        return $next($request);
    }
}

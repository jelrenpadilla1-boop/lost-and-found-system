<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated and is an admin
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Assuming your users table has an 'is_admin' column or 'role' column
        if (Auth::user()->is_admin != 1) { // or Auth::user()->role !== 'admin'
            abort(403, 'Unauthorized access.');
        }

        return $next($request);
    }
}
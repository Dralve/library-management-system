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
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Check if the authenticated user has the 'admin' role
        if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request);
        }

        // If not, return an unauthorized response
        return response()->json(['error' => 'Unauthorized'], 403);
    }
}

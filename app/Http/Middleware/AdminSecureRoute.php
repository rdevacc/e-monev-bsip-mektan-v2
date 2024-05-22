<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminSecureRoute
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        // Check if the user is authenticated and is Super Admin or Admin
        if (!Auth::check() || Auth::user()->role->id != 1 && Auth::user()->role->id != 2 ) {
            // Redirect to login if not authenticated
            return abort(403);
        }

        if(!$request->path() == "v2/app/user/1/edit" || Auth::user()->role->id != 1) {
            // Redirect to login if not authenticated
            return abort(403);
        }


        return $next($request);
    }
}

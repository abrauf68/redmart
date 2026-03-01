<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ApprovelCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Exclude the deactivated page itself from redirection
        if ($request->routeIs('pending')) {
            return $next($request);
        }

        if (Auth::check() && Auth::user()->is_approved !== '1') {
            // Redirect to the deactivation page
            return redirect()->route('pending');
        }
        return $next($request);
    }
}

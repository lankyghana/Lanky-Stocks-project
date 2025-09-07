<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetXFrameOptionsHeader
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        
        // Set X-Frame-Options to DENY for better clickjacking protection
        // Use SAMEORIGIN only if your application needs to be embedded in iframes from the same origin
        $response->headers->set('X-Frame-Options', 'DENY');
        
        return $response;
    }
}
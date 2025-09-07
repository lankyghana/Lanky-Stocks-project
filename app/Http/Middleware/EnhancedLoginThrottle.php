<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class EnhancedLoginThrottle
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): Response
    {
        $ip = $request->ip();
        $key = 'login_attempts:' . $ip;
        $attempts = Cache::get($key, 0);
        
        // Block after 5 failed attempts
        if ($attempts >= 5) {
            $blockedKey = 'login_blocked:' . $ip;
            $blockedUntil = Cache::get($blockedKey);
            
            if ($blockedUntil && now()->timestamp < $blockedUntil) {
                Log::warning('Blocked login attempt from IP', [
                    'ip' => $ip,
                    'attempts' => $attempts,
                    'blocked_until' => date('Y-m-d H:i:s', $blockedUntil)
                ]);
                
                return response()->json([
                    'message' => 'Too many login attempts. Try again later.',
                    'blocked_until' => date('Y-m-d H:i:s', $blockedUntil)
                ], 429);
            }
        }
        
        $response = $next($request);
        
        // If this is a login request and it failed, increment the counter
        if ($request->isMethod('POST') && 
            ($request->is('*/login') || $request->is('login')) && 
            $response->getStatusCode() !== 200 && 
            $response->getStatusCode() !== 302) {
            
            $attempts++;
            Cache::put($key, $attempts, now()->addMinutes(30));
            
            // Block IP for progressively longer periods
            if ($attempts >= 5) {
                $blockDuration = $this->getBlockDuration($attempts);
                $blockedUntil = now()->addMinutes($blockDuration)->timestamp;
                Cache::put('login_blocked:' . $ip, $blockedUntil, now()->addMinutes($blockDuration));
                
                Log::warning('IP blocked due to failed login attempts', [
                    'ip' => $ip,
                    'attempts' => $attempts,
                    'block_duration_minutes' => $blockDuration
                ]);
            }
        }
        
        // If login was successful, clear the attempts
        if ($request->isMethod('POST') && 
            ($request->is('*/login') || $request->is('login')) && 
            ($response->getStatusCode() === 200 || $response->getStatusCode() === 302)) {
            
            Cache::forget($key);
            Cache::forget('login_blocked:' . $ip);
        }
        
        return $response;
    }
    
    /**
     * Get block duration based on number of attempts
     */
    private function getBlockDuration($attempts): int
    {
        return match(true) {
            $attempts >= 10 => 120, // 2 hours
            $attempts >= 8 => 60,   // 1 hour
            $attempts >= 6 => 30,   // 30 minutes
            default => 15           // 15 minutes
        };
    }
}

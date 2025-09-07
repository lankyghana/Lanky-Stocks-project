<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BasicWAF
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
        // Basic WAF protection - block common attack patterns
        $userAgent = $request->header('User-Agent', '');
        $requestUri = $request->getRequestUri();
        $queryString = $request->getQueryString();
        
        // Block suspicious user agents
        $blockedUserAgents = [
            'sqlmap',
            'nikto',
            'nessus',
            'masscan',
            'nmap',
            'python-requests',
            'curl',
            'wget'
        ];
        
        foreach ($blockedUserAgents as $blocked) {
            if (stripos($userAgent, $blocked) !== false) {
                return response('Forbidden', 403);
            }
        }
        
        // Block common SQL injection patterns
        $sqlPatterns = [
            'union\s+select',
            'select\s+.*\s+from',
            'insert\s+into',
            'update\s+.*\s+set',
            'delete\s+from',
            'drop\s+table',
            ';\s*drop',
            ';\s*delete',
            ';\s*insert',
            ';\s*update'
        ];
        
        $fullRequest = $requestUri . ' ' . $queryString . ' ' . json_encode($request->all());
        
        foreach ($sqlPatterns as $pattern) {
            if (preg_match('/' . $pattern . '/i', $fullRequest)) {
                // Log the attempt
                \Log::warning('Potential SQL injection attempt detected', [
                    'ip' => $request->ip(),
                    'user_agent' => $userAgent,
                    'uri' => $requestUri,
                    'pattern' => $pattern
                ]);
                return response('Forbidden', 403);
            }
        }
        
        // Block common XSS patterns
        $xssPatterns = [
            '<script',
            'javascript:',
            'onerror\s*=',
            'onload\s*=',
            'onclick\s*=',
            'onmouseover\s*=',
            'document\.cookie',
            'document\.write'
        ];
        
        foreach ($xssPatterns as $pattern) {
            if (preg_match('/' . $pattern . '/i', $fullRequest)) {
                // Log the attempt
                \Log::warning('Potential XSS attempt detected', [
                    'ip' => $request->ip(),
                    'user_agent' => $userAgent,
                    'uri' => $requestUri,
                    'pattern' => $pattern
                ]);
                return response('Forbidden', 403);
            }
        }
        
        // Block directory traversal attempts
        $traversalPatterns = [
            '\.\.\/',
            '\.\.\\\\',
            '%2e%2e%2f',
            '%2e%2e\\\\',
            '\.\.%2f',
            '\.\.%5c'
        ];
        
        foreach ($traversalPatterns as $pattern) {
            if (preg_match('/' . $pattern . '/i', $requestUri)) {
                // Log the attempt
                \Log::warning('Potential directory traversal attempt detected', [
                    'ip' => $request->ip(),
                    'user_agent' => $userAgent,
                    'uri' => $requestUri,
                    'pattern' => $pattern
                ]);
                return response('Forbidden', 403);
            }
        }
        
        return $next($request);
    }
}

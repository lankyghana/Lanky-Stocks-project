<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
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
        $response = $next($request);

        // Skip if CSP is disabled
        if (!config('security.csp.enabled', true)) {
            return $response;
        }

        // Only apply strict security headers to admin routes
        if ($request->is('admin/*')) {
            // Stricter security for admin panel
            $response->headers->set('X-Frame-Options', config('security.frame_options.admin', 'DENY'));
            $csp = $this->getAdminCSP();
        } else {
            // More permissive for public pages with ads
            $response->headers->set('X-Frame-Options', config('security.frame_options.public', 'SAMEORIGIN'));
            $csp = $this->getPublicCSP();
        }
        
        // Apply CSP header
        $headerName = config('security.csp.report_only', false) ? 'Content-Security-Policy-Report-Only' : 'Content-Security-Policy';
        $response->headers->set($headerName, $csp);

        // Strict Transport Security
        if (config('security.hsts.enabled', true)) {
            $hsts = 'max-age=' . config('security.hsts.max_age', 31536000);
            if (config('security.hsts.include_subdomains', true)) {
                $hsts .= '; includeSubDomains';
            }
            if (config('security.hsts.preload', true)) {
                $hsts .= '; preload';
            }
            $response->headers->set('Strict-Transport-Security', $hsts);
        }

        // Additional Security Headers
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');

        return $response;
    }

    /**
     * Get CSP policy for admin routes (more restrictive)
     */
    private function getAdminCSP(): string
    {
        $scriptSources = array_merge([
            "'self'",
            "'unsafe-inline'",
            "'unsafe-eval'"
        ], config('security.csp.script_sources', []));

        $styleSources = array_merge([
            "'self'",
            "'unsafe-inline'"
        ], config('security.csp.style_sources', []));

        $fontSources = array_merge([
            "'self'",
            "data:"
        ], config('security.csp.font_sources', []));

        $csp = "default-src 'self'; " .
               "script-src " . implode(' ', $scriptSources) . "; " .
               "style-src " . implode(' ', $styleSources) . "; " .
               "font-src " . implode(' ', $fontSources) . "; " .
               "img-src 'self' data: https:; " .
               "connect-src 'self' https: wss: https://api.paystack.co https://standard.paystack.co; " .
               "media-src 'self' https:; " .
               "object-src 'none'; " .
               "base-uri 'self'; " .
               "frame-ancestors 'none'; " .
               "frame-src https://checkout.paystack.com; " .
               "form-action 'self'; " .
               "upgrade-insecure-requests;";

        // Add report URI if configured
        if ($reportUri = config('security.csp.report_uri')) {
            $csp .= " report-uri " . $reportUri . ";";
        }

        return $csp;
    }

    /**
     * Get CSP policy for public routes (allows ads)
     */
    private function getPublicCSP(): string
    {
        $scriptSources = array_merge([
            "'self'",
            "'unsafe-inline'",
            "'unsafe-eval'"
        ], config('security.csp.script_sources', []));

        $styleSources = array_merge([
            "'self'",
            "'unsafe-inline'"
        ], config('security.csp.style_sources', []));

        $fontSources = array_merge([
            "'self'",
            "data:"
        ], config('security.csp.font_sources', []));

        // Add Google Ads domains if enabled
        if (config('security.csp.google_ads_enabled', true)) {
            $googleAdsDomains = [
                'https://www.google.com',
                'https://www.gstatic.com',
                'https://googleads.g.doubleclick.net',
                'https://partner.googleadservices.com',
                'https://tpc.googlesyndication.com',
                'https://pagead2.googlesyndication.com',
                'https://www.googletagmanager.com',
                'https://www.googletagservices.com',
                'https://securepubads.g.doubleclick.net',
                'https://adsystem.google.com',
                'https://googleadservices.com',
                'https://googlesyndication.com',
            ];
            
            $scriptSources = array_merge($scriptSources, $googleAdsDomains);
            $styleSources = array_merge($styleSources, $googleAdsDomains);
        }

        $csp = "default-src 'self'; " .
               "script-src " . implode(' ', $scriptSources) . "; " .
               "style-src " . implode(' ', $styleSources) . "; " .
               "font-src " . implode(' ', $fontSources) . "; " .
               "img-src 'self' data: https: blob:; " .
               "connect-src 'self' https: wss: https://api.paystack.co https://standard.paystack.co; " .
               "media-src 'self' https:; " .
               "object-src 'none'; " .
               "base-uri 'self'; " .
               "frame-ancestors 'none'; " .
               "form-action 'self'; " .
               "upgrade-insecure-requests;";

        // Add frame-src for Google Ads
        if (config('security.csp.google_ads_enabled', true)) {
            $csp .= " frame-src https://googleads.g.doubleclick.net https://partner.googleadservices.com https://tpc.googlesyndication.com https://bid.g.doubleclick.net https://securepubads.g.doubleclick.net https://checkout.paystack.com;";
        } else {
            $csp .= " frame-src https://checkout.paystack.com;";
        }

        // Add report URI if configured
        if ($reportUri = config('security.csp.report_uri')) {
            $csp .= " report-uri " . $reportUri . ";";
        }

        return $csp;
    }
}

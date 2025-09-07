<?php

return [
    
    /*
    |--------------------------------------------------------------------------
    | Security Headers Configuration
    |--------------------------------------------------------------------------
    |
    | This configuration controls the security headers applied to your application.
    | You can customize these settings based on your needs.
    |
    */

    'csp' => [
        'enabled' => env('CSP_ENABLED', false),
        'report_only' => env('CSP_REPORT_ONLY', false),
        'report_uri' => env('CSP_REPORT_URI', null),
        
        // Allow Google Ads domains
        'google_ads_enabled' => env('GOOGLE_ADS_ENABLED', true),
        
        // Additional script sources
        'script_sources' => [
            'https://cdnjs.cloudflare.com',
            'https://cdn.jsdelivr.net',
            'https://unpkg.com',
            'https://js.paystack.co',
        ],
        
        // Additional style sources
        'style_sources' => [
            'https://fonts.googleapis.com',
            'https://cdnjs.cloudflare.com',
            'https://cdn.jsdelivr.net',
        ],
        
        // Additional font sources
        'font_sources' => [
            'https://fonts.gstatic.com',
            'https://cdnjs.cloudflare.com',
        ],
    ],
    
    /*
    |--------------------------------------------------------------------------
    | X-Frame-Options
    |--------------------------------------------------------------------------
    |
    | Controls whether the site can be embedded in frames/iframes
    | Options: DENY, SAMEORIGIN, ALLOW-FROM uri
    |
    */
    
    'frame_options' => [
        'admin' => env('FRAME_OPTIONS_ADMIN', 'DENY'),
        'public' => env('FRAME_OPTIONS_PUBLIC', 'SAMEORIGIN'),
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Strict Transport Security
    |--------------------------------------------------------------------------
    |
    | Forces HTTPS connections and prevents protocol downgrade attacks
    |
    */
    
    'hsts' => [
        'enabled' => env('HSTS_ENABLED', true),
        'max_age' => env('HSTS_MAX_AGE', 31536000), // 1 year
        'include_subdomains' => env('HSTS_INCLUDE_SUBDOMAINS', true),
        'preload' => env('HSTS_PRELOAD', true),
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Web Application Firewall
    |--------------------------------------------------------------------------
    |
    | Basic WAF protection settings
    |
    */
    
    'waf' => [
        'enabled' => env('WAF_ENABLED', true),
        'log_attempts' => env('WAF_LOG_ATTEMPTS', true),
        'block_suspicious_agents' => env('WAF_BLOCK_SUSPICIOUS_AGENTS', true),
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Login Throttling
    |--------------------------------------------------------------------------
    |
    | Rate limiting for login attempts
    |
    */
    
    'login_throttle' => [
        'enabled' => env('LOGIN_THROTTLE_ENABLED', true),
        'max_attempts' => env('LOGIN_THROTTLE_MAX_ATTEMPTS', 5),
        'decay_minutes' => env('LOGIN_THROTTLE_DECAY_MINUTES', 30),
    ],
    
];

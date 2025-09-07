<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Webmail Configuration
    |--------------------------------------------------------------------------
    |
    | Configure your webmail settings here
    |
    */
    
    'enabled' => env('WEBMAIL_ENABLED', true),
    
    'server' => env('WEBMAIL_SERVER', 'storm.thecloudwebhosts.com'),
    
    'port' => env('WEBMAIL_PORT', 2096),
    
    'ssl' => env('WEBMAIL_SSL', true),
    
    'session_path' => env('WEBMAIL_SESSION_PATH', 'cpsess6486732714'),
    
    'interface' => env('WEBMAIL_INTERFACE', 'roundcube'), // roundcube, squirrelmail, horde
    
    'default_params' => [
        '_task' => 'mail',
        '_mbox' => 'INBOX'
    ],
    
    'admin_access' => env('WEBMAIL_ADMIN_ACCESS', true),
    
    'user_access' => env('WEBMAIL_USER_ACCESS', false),
    
    'timeout' => env('WEBMAIL_TIMEOUT', 10),
];

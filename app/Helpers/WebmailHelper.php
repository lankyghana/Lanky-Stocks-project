<?php

namespace App\Helpers;

class WebmailHelper
{
    /**
     * Get the webmail URL for the current user
     */
    public static function getWebmailUrl($email = null)
    {
        // Base webmail URL
        $baseUrl = 'https://storm.thecloudwebhosts.com:2096/cpsess6486732714/3rdparty/roundcube/';
        
        // Default parameters
        $params = [
            '_task' => 'mail',
            '_mbox' => 'INBOX'
        ];
        
        // If email is provided, add it to the URL
        if ($email) {
            $params['_user'] = $email;
        }
        
        return $baseUrl . '?' . http_build_query($params);
    }
    
    /**
     * Get webmail URL for admin
     */
    public static function getAdminWebmailUrl()
    {
        return self::getWebmailUrl();
    }
    
    /**
     * Check if webmail is accessible
     */
    public static function isWebmailAccessible()
    {
        $url = self::getWebmailUrl();
        
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_NOBODY, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            
            curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            return $httpCode === 200;
        } catch (Exception $e) {
            return false;
        }
    }
    
    /**
     * Get webmail configuration
     */
    public static function getWebmailConfig()
    {
        return [
            'url' => self::getWebmailUrl(),
            'accessible' => self::isWebmailAccessible(),
            'server' => 'storm.thecloudwebhosts.com',
            'port' => 2096,
            'type' => 'Roundcube'
        ];
    }
}

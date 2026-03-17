<?php
// /kwetu_con/config/environment.php

/**
 * Détection automatique de l'environnement
 */
class Environment {
    
    public static function detect() {
        $host = $_SERVER['HTTP_HOST'] ?? '';
        $serverName = $_SERVER['SERVER_NAME'] ?? '';
        
        // Détection environnement
        if (strpos($host, 'localhost') !== false || 
            strpos($host, '127.0.0.1') !== false ||
            $serverName == 'localhost') {
            return 'local';
        }
        
        // Détection production (Hostinger)
        if (strpos($host, 'kwetucon.online') !== false ||
            strpos($serverName, 'kwetucon.online') !== false) {
            return 'production';
        }
        
        // Test environment
        if (strpos($host, 'test.kwetucon.online') !== false) {
            return 'test';
        }
        
        return 'production'; // Par défaut
    }
    
    public static function isLocal() {
        return self::detect() === 'local';
    }
    
    public static function isProduction() {
        return self::detect() === 'production';
    }
    
    public static function isTest() {
        return self::detect() === 'test';
    }
    
    public static function getBaseUrl() {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        
        if (self::isLocal()) {
            return $protocol . $host . '/kwetu_con';
        }
        
        return $protocol . $host;
    }
    
    public static function getAssetPath() {
        if (self::isLocal()) {
            return '/kwetu_con/public/assets';
        }
        return '/public/assets';
    }
    
    public static function getRootPath() {
        if (self::isLocal()) {
            return '/kwetu_con';
        }
        return '';
    }
}
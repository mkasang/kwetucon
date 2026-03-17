<?php
// /kwetu_con/config/app.php

return [
    // Nom de l'application
    'name' => 'KWETU CON',
    
    // Environnement (development/production)
    'env' => 'development',
    
    // Mode debug
    'debug' => true,
    
    // URL de base
    'url' => 'http://localhost/kwetu_con',
    
    // Timezone
    'timezone' => 'Africa/Lubumbashi',
    
    // Locale
    'locale' => 'fr',
    
    // Clé secrète pour les tokens
    'secret_key' => 'votre_cle_secrete_tres_longue_et_complexe_123456789',
    
    // Configuration des uploads
    'uploads' => [
        'max_size' => 5 * 1024 * 1024, // 5MB
        'allowed_types' => ['image/jpeg', 'image/png', 'image/gif'],
        'path' => storage_path('uploads')
    ],
    
    // Configuration des sessions
    'session' => [
        'name' => 'kwetu_session',
        'lifetime' => 7200, // 2 heures
        'secure' => false, // true en production avec HTTPS
        'httponly' => true
    ]
];
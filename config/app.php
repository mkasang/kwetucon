<?php
// /kwetu_con/config/app.php

return [
    'name' => 'KWETU CON',
    'env' => 'development',
    'debug' => true,
    'url' => 'http://localhost/kwetu_con',
    'timezone' => 'Africa/Lubumbashi',
    'locale' => 'fr',
    'secret_key' => 'votre_cle_secrete_tres_longue_et_complexe_123456789',
    'uploads' => [
        'max_size' => 5 * 1024 * 1024,
        'allowed_types' => ['image/jpeg', 'image/png', 'image/gif'],
        'path' => dirname(__DIR__) . '/storage/uploads'
    ],
    'session' => [
        'name' => 'kwetu_session',
        'lifetime' => 7200,
        'secure' => false,
        'httponly' => true
    ]
];
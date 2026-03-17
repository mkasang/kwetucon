<?php
// /kwetu_con/public/index.php

/**
 * Front Controller de KWETU CON
 * Toutes les requêtes passent par ici
 */

// Démarrer la session
session_start();

// Charger les helpers
require_once dirname(__DIR__) . '/helpers.php';

// Charger la configuration
$config = require_once config_path('app.php');

// Configuration des erreurs
if ($config['debug'] ?? false) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    error_reporting(0);
}

// Charger le routeur
require_once app_path('core/Router.php');
require_once app_path('core/AuthMiddleware.php');
require_once app_path('core/AdminMiddleware.php');

// Charger les modèles de base (sera fait plus tard)
// require_once app_path('core/BaseModel.php');

// Initialiser le routeur
$router = new Router();

// Middlewares globaux
$router->addMiddleware(function() {
    // Vérifier le mode maintenance
    // À implémenter avec la base de données
    return true;
});

// Charger les routes web
require_once routes_path('web.php');

// Charger les routes API
require_once routes_path('api.php');

// Exécuter le routeur
$router->run();
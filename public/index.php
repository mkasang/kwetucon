<?php
// /kwetu_con/public/index.php

// Définir le chemin de base
define('BASE_PATH', dirname(__DIR__));

// Démarrer la session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Charger les helpers
require_once BASE_PATH . '/helpers.php';

// Charger la configuration
$configFile = config_path('app.php');
if (file_exists($configFile)) {
    $config = require $configFile;
} else {
    $config = [
        'debug' => true,
        'name' => 'KWETU CON'
    ];
}

// Configuration des erreurs
if ($config['debug'] ?? false) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    error_reporting(0);
}

// Charger les classes core
require_once app_path('core/Router.php');
require_once app_path('core/BaseController.php');
require_once app_path('core/BaseModel.php');
require_once app_path('core/Validator.php');
require_once app_path('core/AuthHelper.php');
require_once app_path('core/View.php');
require_once app_path('core/AuthMiddleware.php');
require_once app_path('core/AdminMiddleware.php');

// Initialiser le routeur
$router = new Router();

// Middlewares globaux
$router->addMiddleware(function() {
    // Vérifier le mode maintenance (à implémenter plus tard)
    return true;
});

// Charger les routes web
$webRoutes = routes_path('web.php');
if (file_exists($webRoutes)) {
    require $webRoutes;
}

// Charger les routes API
$apiRoutes = routes_path('api.php');
if (file_exists($apiRoutes)) {
    require $apiRoutes;
}

// Route par défaut si aucune route trouvée
$router->get('/', function() {
    // Rediriger vers HomeController
    require_once app_path('controllers/HomeController.php');
    $controller = new HomeController();
    $controller->index();
});

// Exécuter le routeur
try {
    $router->run();
} catch (Exception $e) {
    error_log("Router error: " . $e->getMessage());
    
    if ($config['debug'] ?? false) {
        echo "<h3>Erreur:</h3>";
        echo "<p>" . $e->getMessage() . "</p>";
        echo "<pre>" . $e->getTraceAsString() . "</pre>";
    } else {
        header("HTTP/1.0 500 Internal Server Error");
        require_once app_path('views/public/500.php');
    }
}
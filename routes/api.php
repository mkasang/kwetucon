<?php
// /kwetu_con/routes/api.php

/**
 * Routes API de KWETU CON
 * Accessibles via mobile et applications tierces
 * Retourne toujours du JSON
 */

// API v1
$api_prefix = '/api/v1';

// Routes publiques API
$router->get($api_prefix . '/health', function() {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'message' => 'KWETU CON API is running',
        'version' => '1.0.0',
        'timestamp' => time()
    ]);
});

// Authentification API
$router->post($api_prefix . '/register', 'Api\AuthController@register');
$router->post($api_prefix . '/login', 'Api\AuthController@login');
$router->post($api_prefix . '/logout', 'Api\AuthController@logout', 'AuthMiddleware');

// Profils API
$router->get($api_prefix . '/profile', 'Api\ProfileController@show', 'AuthMiddleware');
$router->post($api_prefix . '/profile/update', 'Api\ProfileController@update', 'AuthMiddleware');
$router->post($api_prefix . '/profile/upload-photo', 'Api\ProfileController@uploadPhoto', 'AuthMiddleware');
$router->delete($api_prefix . '/profile/photo/{id}', 'Api\ProfileController@deletePhoto', 'AuthMiddleware');
$router->get($api_prefix . '/profile/{id}', 'Api\ProfileController@view', 'AuthMiddleware');

// Découverte API
$router->get($api_prefix . '/discover', 'Api\DiscoverController@index', 'AuthMiddleware');
$router->get($api_prefix . '/discover/nearby', 'Api\DiscoverController@nearby', 'AuthMiddleware');
$router->get($api_prefix . '/discover/filter', 'Api\DiscoverController@filter', 'AuthMiddleware');

// Conversations API
$router->get($api_prefix . '/conversations', 'Api\ConversationController@index', 'AuthMiddleware');
$router->get($api_prefix . '/conversations/{id}', 'Api\ConversationController@show', 'AuthMiddleware');
$router->post($api_prefix . '/conversations', 'Api\ConversationController@create', 'AuthMiddleware');
$router->delete($api_prefix . '/conversations/{id}', 'Api\ConversationController@delete', 'AuthMiddleware');

// Messages API
$router->get($api_prefix . '/conversations/{id}/messages', 'Api\MessageController@index', 'AuthMiddleware');
$router->post($api_prefix . '/conversations/{id}/messages', 'Api\MessageController@send', 'AuthMiddleware');
$router->put($api_prefix . '/messages/{id}/read', 'Api\MessageController@markAsRead', 'AuthMiddleware');
$router->delete($api_prefix . '/messages/{id}', 'Api\MessageController@delete', 'AuthMiddleware');

// Interactions API
$router->post($api_prefix . '/like/{userId}', 'Api\InteractionController@like', 'AuthMiddleware');
$router->delete($api_prefix . '/like/{userId}', 'Api\InteractionController@unlike', 'AuthMiddleware');
$router->get($api_prefix . '/likes', 'Api\InteractionController@getLikes', 'AuthMiddleware');

$router->post($api_prefix . '/favorite/{userId}', 'Api\InteractionController@addFavorite', 'AuthMiddleware');
$router->delete($api_prefix . '/favorite/{userId}', 'Api\InteractionController@removeFavorite', 'AuthMiddleware');
$router->get($api_prefix . '/favorites', 'Api\InteractionController@getFavorites', 'AuthMiddleware');

// Blocage et signalement API
$router->post($api_prefix . '/block/{userId}', 'Api\BlockController@block', 'AuthMiddleware');
$router->delete($api_prefix . '/block/{userId}', 'Api\BlockController@unblock', 'AuthMiddleware');
$router->get($api_prefix . '/blocks', 'Api\BlockController@index', 'AuthMiddleware');

$router->post($api_prefix . '/report', 'Api\ReportController@report', 'AuthMiddleware');
$router->get($api_prefix . '/reports', 'Api\ReportController@index', 'AuthMiddleware'); // Pour admin

// Publicités API
$router->get($api_prefix . '/ads', 'Api\AdController@index', 'AuthMiddleware');
$router->post($api_prefix . '/ads/{id}/view', 'Api\AdController@trackView', 'AuthMiddleware');
$router->post($api_prefix . '/ads/{id}/click', 'Api\AdController@trackClick', 'AuthMiddleware');

// Notifications API
$router->get($api_prefix . '/notifications', 'Api\NotificationController@index', 'AuthMiddleware');
$router->put($api_prefix . '/notifications/{id}/read', 'Api\NotificationController@markAsRead', 'AuthMiddleware');
$router->put($api_prefix . '/notifications/read-all', 'Api\NotificationController@markAllAsRead', 'AuthMiddleware');
$router->get($api_prefix . '/notifications/count', 'Api\NotificationController@getUnreadCount', 'AuthMiddleware');

// Statistiques API (admin uniquement)
$router->get($api_prefix . '/admin/stats', 'Api\Admin\StatsController@index', 'AdminMiddleware');
$router->get($api_prefix . '/admin/users', 'Api\Admin\UserController@index', 'AdminMiddleware');
$router->put($api_prefix . '/admin/users/{id}/status', 'Api\Admin\UserController@updateStatus', 'AdminMiddleware');
$router->get($api_prefix . '/admin/reports', 'Api\Admin\ReportController@index', 'AdminMiddleware');
$router->put($api_prefix . '/admin/reports/{id}', 'Api\Admin\ReportController@update', 'AdminMiddleware');

// Version API
$router->get($api_prefix . '/version', function() {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => true,
        'version' => '1.0.0',
        'min_version' => '1.0.0',
        'update_url' => 'https://kwetucon.com/update'
    ]);
});
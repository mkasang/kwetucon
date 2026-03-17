<?php
// /kwetu_con/routes/web.php

/**
 * Routes Web de KWETU CON
 * Accessibles uniquement via navigateur
 */

// Routes publiques
$router->get('/', 'HomeController@index');
$router->get('/about', 'HomeController@about');
$router->get('/contact', 'HomeController@contact');
$router->post('/contact', 'HomeController@sendContact');

// Routes d'authentification
$router->get('/login', 'AuthController@showLogin');
$router->post('/login', 'AuthController@login');
$router->get('/register', 'AuthController@showRegister');
$router->post('/register', 'AuthController@register');
$router->get('/logout', 'AuthController@logout');

// Routes utilisateur (protégées)
$router->get('/profile', 'ProfileController@show', 'AuthMiddleware');
$router->post('/profile/update', 'ProfileController@update', 'AuthMiddleware');
$router->post('/profile/upload-photo', 'ProfileController@uploadPhoto', 'AuthMiddleware');
$router->get('/profile/{id}', 'ProfileController@view', 'AuthMiddleware');

$router->get('/discover', 'DiscoverController@index', 'AuthMiddleware');
$router->get('/discover/filter', 'DiscoverController@filter', 'AuthMiddleware');

$router->get('/conversations', 'ConversationController@index', 'AuthMiddleware');
$router->get('/conversation/{id}', 'ConversationController@show', 'AuthMiddleware');
$router->post('/conversation/{id}/send', 'MessageController@send', 'AuthMiddleware');
$router->get('/conversation/start/{userId}', 'ConversationController@start', 'AuthMiddleware');

$router->post('/block/{userId}', 'BlockController@block', 'AuthMiddleware');
$router->post('/unblock/{userId}', 'BlockController@unblock', 'AuthMiddleware');
$router->post('/report/{userId}', 'ReportController@report', 'AuthMiddleware');

$router->get('/settings', 'SettingsController@index', 'AuthMiddleware');
$router->post('/settings/update', 'SettingsController@update', 'AuthMiddleware');

// Routes admin (protégées)
$router->get('/admin', 'Admin\DashboardController@index', 'AdminMiddleware');
$router->get('/admin/users', 'Admin\UserController@index', 'AdminMiddleware');
$router->get('/admin/users/{id}', 'Admin\UserController@show', 'AdminMiddleware');
$router->post('/admin/users/{id}/block', 'Admin\UserController@block', 'AdminMiddleware');
$router->post('/admin/users/{id}/verify', 'Admin\UserController@verify', 'AdminMiddleware');

$router->get('/admin/reports', 'Admin\ReportController@index', 'AdminMiddleware');
$router->get('/admin/reports/{id}', 'Admin\ReportController@show', 'AdminMiddleware');
$router->post('/admin/reports/{id}/resolve', 'Admin\ReportController@resolve', 'AdminMiddleware');

$router->get('/admin/ads', 'Admin\AdController@index', 'AdminMiddleware');
$router->get('/admin/ads/create', 'Admin\AdController@create', 'AdminMiddleware');
$router->post('/admin/ads', 'Admin\AdController@store', 'AdminMiddleware');
$router->get('/admin/ads/{id}/edit', 'Admin\AdController@edit', 'AdminMiddleware');
$router->post('/admin/ads/{id}', 'Admin\AdController@update', 'AdminMiddleware');
$router->post('/admin/ads/{id}/delete', 'Admin\AdController@delete', 'AdminMiddleware');

$router->get('/admin/settings', 'Admin\SettingController@index', 'AdminMiddleware');
$router->post('/admin/settings', 'Admin\SettingController@update', 'AdminMiddleware');
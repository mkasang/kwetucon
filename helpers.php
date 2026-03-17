<?php
// /kwetu_con/helpers.php

/**
 * Fonctions globales pour l'application
 */

function base_path($path = '') {
    return dirname(__FILE__) . '/' . ltrim($path, '/');
}

function app_path($path = '') {
    return base_path('app/' . ltrim($path, '/'));
}

function public_path($path = '') {
    return base_path('public/' . ltrim($path, '/'));
}

function config_path($path = '') {
    return base_path('config/' . ltrim($path, '/'));
}

function routes_path($path = '') {
    return base_path('routes/' . ltrim($path, '/'));
}

function storage_path($path = '') {
    return base_path('storage/' . ltrim($path, '/'));
}

function asset($path) {
    return '/kwetu_con/public/assets/' . ltrim($path, '/');
}

function url($path = '') {
    return '/kwetu_con/' . ltrim($path, '/');
}

function redirect($path) {
    header('Location: ' . url($path));
    exit;
}

function dd($data) {
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
    die();
}

function sanitize($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

function generate_token() {
    return bin2hex(random_bytes(32));
}

function is_logged_in() {
    return isset($_SESSION['user_id']);
}

function is_admin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

function get_current_user_id() {
    return $_SESSION['user_id'] ?? null;
}
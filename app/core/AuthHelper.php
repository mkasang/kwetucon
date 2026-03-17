<?php
// /kwetu_con/app/core/AuthHelper.php

/**
 * Gestionnaire d'authentification pour KWETU CON
 */
class AuthHelper {
    
    /**
     * Vérifier si l'utilisateur est connecté
     */
    public static function check() {
        return isset($_SESSION['user_id']);
    }
    
    /**
     * Vérifier si l'utilisateur est admin
     */
    public static function isAdmin() {
        return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
    }
    
    /**
     * Obtenir l'ID de l'utilisateur connecté
     */
    public static function id() {
        return $_SESSION['user_id'] ?? null;
    }
    
    /**
     * Obtenir le rôle de l'utilisateur
     */
    public static function role() {
        return $_SESSION['user_role'] ?? 'guest';
    }
    
    /**
     * Connecter un utilisateur
     */
    public static function login($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role'] = $user['role'] ?? 'user';
        $_SESSION['logged_in_at'] = time();
        
        // Régénérer l'ID de session pour sécurité
        session_regenerate_id(true);
    }
    
    /**
     * Déconnecter l'utilisateur
     */
    public static function logout() {
        $_SESSION = [];
        
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        
        session_destroy();
    }
    
    /**
     * Vérifier le token API
     */
    public static function verifyApiToken($token) {
        // À implémenter avec la base de données
        // Retourne l'ID utilisateur si valide, sinon false
        return false;
    }
    
    /**
     * Générer un token API
     */
    public static function generateApiToken($userId) {
        return bin2hex(random_bytes(32));
    }
    
    /**
     * Vérifier le mot de passe
     */
    public static function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }
    
    /**
     * Hacher un mot de passe
     */
    public static function hashPassword($password) {
        return password_hash($password, PASSWORD_DEFAULT);
    }
    
    /**
     * Vérifier l'âge minimum
     */
    public static function checkMinimumAge($birthDate, $minAge = 18) {
        $birth = new DateTime($birthDate);
        $today = new DateTime('today');
        $age = $birth->diff($today)->y;
        
        return $age >= $minAge;
    }
}
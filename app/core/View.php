<?php
// /kwetu_con/app/core/View.php

/**
 * Gestionnaire de vues pour KWETU CON
 */
class View {
    
    /**
     * Charger un partial
     */
    public static function partial($partial, $data = []) {
        extract($data);
        
        $partialFile = app_path("views/partials/{$partial}.php");
        
        if (file_exists($partialFile)) {
            require $partialFile;
        }
    }
    
    /**
     * Charger un header
     */
    public static function header($type = 'public', $data = []) {
        self::partial("header/header_{$type}", $data);
    }
    
    /**
     * Charger un footer
     */
    public static function footer($type = 'public', $data = []) {
        self::partial("footer/footer_{$type}", $data);
    }
    
    /**
     * Générer une URL d'asset
     */
    public static function asset($path) {
        return '/kwetu_con/public/assets/' . ltrim($path, '/');
    }
    
    /**
     * Générer une URL
     */
    public static function url($path) {
        return '/kwetu_con/' . ltrim($path, '/');
    }
    
    /**
     * Échapper les données pour HTML
     */
    public static function escape($data) {
        return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    }
    
    /**
     * Formater une date
     */
    public static function formatDate($date, $format = 'd/m/Y') {
        if (empty($date)) {
            return '';
        }
        
        $datetime = new DateTime($date);
        return $datetime->format($format);
    }
    
    /**
     * Tronquer un texte
     */
    public static function truncate($text, $length = 100, $suffix = '...') {
        if (strlen($text) <= $length) {
            return $text;
        }
        
        return substr($text, 0, $length) . $suffix;
    }
    
    /**
     * Obtenir l'âge à partir d'une date de naissance
     */
    public static function getAge($birthDate) {
        $birth = new DateTime($birthDate);
        $today = new DateTime('today');
        return $birth->diff($today)->y;
    }
    
    /**
     * Afficher un message flash
     */
    public static function flashMessage() {
        if (isset($_SESSION['flash_message'])) {
            $message = $_SESSION['flash_message'];
            unset($_SESSION['flash_message']);
            return "<div class='alert alert-success'>{$message}</div>";
        }
        
        return '';
    }
    
    /**
     * Afficher une erreur flash
     */
    public static function flashError() {
        if (isset($_SESSION['flash_error'])) {
            $error = $_SESSION['flash_error'];
            unset($_SESSION['flash_error']);
            return "<div class='alert alert-danger'>{$error}</div>";
        }
        
        return '';
    }
}
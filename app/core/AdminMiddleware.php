<?php
// /kwetu_con/app/core/AdminMiddleware.php

class AdminMiddleware {
    
    /**
     * Vérifier si l'utilisateur est admin
     */
    public function handle() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            // Redirection pour les requêtes web
            if (strpos($_SERVER['REQUEST_URI'], '/api/') === false) {
                $_SESSION['flash_error'] = 'Accès non autorisé';
                header('Location: /kwetu_con/');
                exit;
            } 
            // Réponse JSON pour les requêtes API
            else {
                header('Content-Type: application/json');
                http_response_code(403);
                echo json_encode([
                    'success' => false,
                    'error' => 'Accès non autorisé'
                ]);
                exit;
            }
        }
        return true;
    }
}
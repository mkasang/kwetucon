<?php
// /kwetu_con/app/core/AuthMiddleware.php

class AuthMiddleware {
    
    /**
     * Vérifier si l'utilisateur est connecté
     */
    public function handle() {
        if (!isset($_SESSION['user_id'])) {
            // Redirection pour les requêtes web
            if (strpos($_SERVER['REQUEST_URI'], '/api/') === false) {
                $_SESSION['flash_error'] = 'Vous devez être connecté pour accéder à cette page';
                header('Location: /kwetu_con/login');
                exit;
            } 
            // Réponse JSON pour les requêtes API
            else {
                header('Content-Type: application/json');
                http_response_code(401);
                echo json_encode([
                    'success' => false,
                    'error' => 'Non authentifié'
                ]);
                exit;
            }
        }
        return true;
    }
}
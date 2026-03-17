<?php
// /kwetu_con/app/core/BaseController.php

/**
 * Base Controller pour KWETU CON
 * Fournit des méthodes communes à tous les contrôleurs
 */
class BaseController {
    
    protected $data = [];
    
    /**
     * Constructeur
     */
    public function __construct() {
        $this->init();
    }
    
    /**
     * Initialisation
     */
    protected function init() {
        // Données communes à toutes les vues
        $this->data['site_name'] = 'KWETU CON';
        $this->data['current_user'] = $this->getCurrentUser();
        $this->data['flash_message'] = $this->getFlashMessage();
        $this->data['flash_error'] = $this->getFlashError();
         $this->data['request_uri'] = $_SERVER['REQUEST_URI'] ?? '';
    }
    
    /**
     * Charger une vue
     */
    protected function view($view, $data = []) {
        // Fusionner les données
        $data = array_merge($this->data, $data);
        
        // Extraire les variables pour la vue
        extract($data);
        
        // Construire le chemin de la vue
        $viewFile = app_path("views/{$view}.php");
        
        if (!file_exists($viewFile)) {
            die("Vue non trouvée: {$view}");
        }
        
        // Charger le layout approprié
        $layout = $this->getLayout($view);
        
        if ($layout) {
            ob_start();
            require $viewFile;
            $content = ob_get_clean();
            
            require $layout;
        } else {
            require $viewFile;
        }
    }
    
    /**
     * Déterminer le layout à utiliser
     */
    protected function getLayout($view) {
        if (strpos($view, 'admin/') === 0) {
            return app_path('layouts/admin_layout.php');
        } elseif (strpos($view, 'user/') === 0) {
            return app_path('layouts/user_layout.php');
        } elseif (strpos($view, 'public/') === 0 || $view === 'home') {
            return app_path('layouts/public_layout.php');
        }
        
        return null;
    }
    
    /**
     * Retourner une réponse JSON
     */
    protected function json($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    /**
     * Retourner une réponse de succès JSON
     */
    protected function success($data = null, $message = 'Succès') {
        return $this->json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ]);
    }
    
    /**
     * Retourner une réponse d'erreur JSON
     */
    protected function error($message = 'Erreur', $statusCode = 400, $errors = null) {
        return $this->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors
        ], $statusCode);
    }
    
    /**
     * Rediriger vers une URL
     */
    protected function redirect($url, $flashMessage = null, $flashError = null) {
        if ($flashMessage) {
            $_SESSION['flash_message'] = $flashMessage;
        }
        
        if ($flashError) {
            $_SESSION['flash_error'] = $flashError;
        }
        
        header("Location: " . url($url));
        exit;
    }
    
    /**
     * Rediriger vers la page précédente
     */
    protected function back($flashMessage = null, $flashError = null) {
        $referer = $_SERVER['HTTP_REFERER'] ?? '/';
        $this->redirect($referer, $flashMessage, $flashError);
    }
    
    /**
     * Obtenir l'utilisateur courant
     */
    protected function getCurrentUser() {
        if (!isset($_SESSION['user_id'])) {
            return null;
        }
        
        // À implémenter avec UserModel
        // return (new UserModel())->find($_SESSION['user_id']);
        
        return [
            'id' => $_SESSION['user_id'],
            'role' => $_SESSION['user_role'] ?? 'user'
        ];
    }
    
    /**
     * Obtenir un message flash
     */
    protected function getFlashMessage() {
        $message = $_SESSION['flash_message'] ?? null;
        unset($_SESSION['flash_message']);
        return $message;
    }
    
    /**
     * Obtenir une erreur flash
     */
    protected function getFlashError() {
        $error = $_SESSION['flash_error'] ?? null;
        unset($_SESSION['flash_error']);
        return $error;
    }
    
    /**
     * Valider les données
     */
    protected function validate($data, $rules) {
        $validator = new Validator();
        return $validator->validate($data, $rules);
    }
    
    /**
     * Obtenir une requête POST
     */
    protected function post($key = null, $default = null) {
        if ($key === null) {
            return $_POST;
        }
        
        return $_POST[$key] ?? $default;
    }
    
    /**
     * Obtenir une requête GET
     */
    protected function get($key = null, $default = null) {
        if ($key === null) {
            return $_GET;
        }
        
        return $_GET[$key] ?? $default;
    }
    
    /**
     * Obtenir les fichiers uploadés
     */
    protected function file($key) {
        return $_FILES[$key] ?? null;
    }
    
    /**
     * Vérifier si la requête est AJAX
     */
    protected function isAjax() {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
    
    /**
     * Vérifier si la requête est une API
     */
    protected function isApi() {
        return strpos($_SERVER['REQUEST_URI'], '/api/') !== false;
    }
}
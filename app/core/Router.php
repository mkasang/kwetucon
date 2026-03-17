<?php
// /kwetu_con/app/core/Router.php

/**
 * Routeur personnalisé pour KWETU CON
 * Gère les routes web et API sans extension .php dans les URLs
 */
class Router {
    private $routes = [];
    private $params = [];
    private $middlewares = [];
    
    /**
     * Ajouter une route GET
     */
    public function get($path, $callback, $middleware = null) {
        $this->addRoute('GET', $path, $callback, $middleware);
    }
    
    /**
     * Ajouter une route POST
     */
    public function post($path, $callback, $middleware = null) {
        $this->addRoute('POST', $path, $callback, $middleware);
    }
    
    /**
     * Ajouter une route PUT
     */
    public function put($path, $callback, $middleware = null) {
        $this->addRoute('PUT', $path, $callback, $middleware);
    }
    
    /**
     * Ajouter une route DELETE
     */
    public function delete($path, $callback, $middleware = null) {
        $this->addRoute('DELETE', $path, $callback, $middleware);
    }
    
    /**
     * Ajouter une route pour toutes les méthodes
     */
    public function any($path, $callback, $middleware = null) {
        $this->addRoute('ANY', $path, $callback, $middleware);
    }
    
    /**
     * Ajouter une route à la collection
     */
    private function addRoute($method, $path, $callback, $middleware) {
        // Nettoyer le chemin
        $path = trim($path, '/');
        $path = $path === '' ? '/' : $path;
        
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'callback' => $callback,
            'middleware' => $middleware
        ];
    }
    
    /**
     * Ajouter un middleware global
     */
    public function addMiddleware($middleware) {
        $this->middlewares[] = $middleware;
    }
    
    /**
     * Exécuter les middlewares
     */
    private function runMiddlewares() {
        foreach ($this->middlewares as $middleware) {
            if (is_callable($middleware)) {
                $result = $middleware();
                if ($result === false) {
                    return false;
                }
            } elseif (class_exists($middleware)) {
                $mw = new $middleware();
                if (method_exists($mw, 'handle')) {
                    $result = $mw->handle();
                    if ($result === false) {
                        return false;
                    }
                }
            }
        }
        return true;
    }
    
    /**
     * Faire correspondre l'URL avec les routes
     */
    private function matchRoute($url, $method) {
        $url = trim($url, '/');
        $url = $url === '' ? '/' : $url;
        
        foreach ($this->routes as $route) {
            // Vérifier la méthode
            if ($route['method'] !== 'ANY' && $route['method'] !== $method) {
                continue;
            }
            
            // Convertir les paramètres dynamiques {id} en regex
            $pattern = preg_replace('/\{([a-z]+)\}/', '(?P<$1>[^/]+)', $route['path']);
            $pattern = '#^' . $pattern . '$#';
            
            if (preg_match($pattern, $url, $matches)) {
                // Extraire les paramètres nommés
                foreach ($matches as $key => $value) {
                    if (is_string($key)) {
                        $this->params[$key] = $value;
                    }
                }
                return $route;
            }
        }
        
        return false;
    }
    
    /**
     * Exécuter le routeur
     */
    public function run() {
        try {
            // Exécuter les middlewares globaux
            if (!$this->runMiddlewares()) {
                return;
            }
            
            // Récupérer l'URL et la méthode
            $url = isset($_GET['url']) ? $_GET['url'] : '/';
            $method = $_SERVER['REQUEST_METHOD'];
            
            // Gérer les méthodes PUT/DELETE via POST
            if ($method === 'POST' && isset($_POST['_method'])) {
                $method = strtoupper($_POST['_method']);
            }
            
            // Chercher la route correspondante
            $route = $this->matchRoute($url, $method);
            
            if ($route === false) {
                $this->notFound();
                return;
            }
            
            // Exécuter le middleware de la route si présent
            if ($route['middleware']) {
                $middleware = $route['middleware'];
                if (is_callable($middleware)) {
                    $result = $middleware();
                    if ($result === false) {
                        return;
                    }
                } elseif (class_exists($middleware)) {
                    $mw = new $middleware();
                    if (method_exists($mw, 'handle')) {
                        $result = $mw->handle();
                        if ($result === false) {
                            return;
                        }
                    }
                }
            }
            
            // Exécuter le callback
            $callback = $route['callback'];
            
            if (is_callable($callback)) {
                // Fonction anonyme
                call_user_func_array($callback, $this->params);
            } elseif (is_string($callback)) {
                // Controller@method
                list($controller, $method) = explode('@', $callback);
                
                $controllerFile = app_path("controllers/{$controller}.php");
                
                if (file_exists($controllerFile)) {
                    require_once $controllerFile;
                    
                    if (class_exists($controller)) {
                        $controllerInstance = new $controller();
                        
                        if (method_exists($controllerInstance, $method)) {
                            call_user_func_array([$controllerInstance, $method], $this->params);
                        } else {
                            throw new Exception("Méthode {$method} non trouvée dans {$controller}");
                        }
                    } else {
                        throw new Exception("Controller {$controller} non trouvé");
                    }
                } else {
                    throw new Exception("Fichier controller {$controller}.php non trouvé");
                }
            }
            
        } catch (Exception $e) {
            $this->handleError($e);
        }
    }
    
    /**
     * Gérer les erreurs 404
     */
    private function notFound() {
        header("HTTP/1.0 404 Not Found");
        
        // Détecter si c'est une requête API
        if (strpos($_SERVER['REQUEST_URI'], '/api/') !== false) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'error' => 'Route non trouvée'
            ]);
        } else {
            require_once app_path('views/public/404.php');
        }
    }
    
    /**
     * Gérer les erreurs
     */
    private function handleError($e) {
        error_log($e->getMessage());
        
        if (strpos($_SERVER['REQUEST_URI'], '/api/') !== false) {
            header('Content-Type: application/json');
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'error' => 'Erreur interne du serveur'
            ]);
        } else {
            require_once app_path('views/public/500.php');
        }
    }
    
    /**
     * Obtenir les paramètres
     */
    public function getParams() {
        return $this->params;
    }
    
    /**
     * Obtenir un paramètre spécifique
     */
    public function getParam($key, $default = null) {
        return isset($this->params[$key]) ? $this->params[$key] : $default;
    }
}
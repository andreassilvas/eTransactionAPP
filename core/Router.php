<?php
// Classe pour gérer le routage des requêtes HTTP
class Router
{
    // Tableau contenant toutes les routes définies
    private $routes = [];

    // Définir une route GET
    public function get($path, $callback)
    {
        $this->routes['GET'][$path] = $callback;
    }

    // Définir une route POST
    public function post($path, $callback)
    {
        $this->routes['POST'][$path] = $callback;
    }

    // Exécuter la route correspondant à la requête actuelle
    public function run($requestPath = null)
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = $requestPath ?? parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Vérifier si la route existe
        if (!isset($this->routes[$method][$path])) {
            http_response_code(404);
            echo "404 Not Found";
            return;
        }

        $callback = $this->routes[$method][$path];

        // Si la route est sous la forme 'Controller@action'
        if (is_string($callback)) {
            [$controller, $action] = explode('@', $callback);

            // Ajouter le namespace du contrôleur
            $controllerClass = "\\App\\Controllers\\$controller";

            // Inclure le fichier du contrôleur si nécessaire (utile si pas d'autoload)
            $file = __DIR__ . "/../app/controllers/$controller.php";
            if (file_exists($file)) {
                require_once $file;
            }

            $controllerObj = new $controllerClass();
            call_user_func([$controllerObj, $action]);
        } else {
            call_user_func($callback);
        }
    }

}

<?php

/* Inclure les fichiers principaux */
require_once __DIR__ . '/app/Helpers/AuthHelper.php';
require_once __DIR__ . '/core/Router.php';
require_once __DIR__ . '/core/Controller.php';
require_once __DIR__ . '/core/Model.php';


/* Charger l'initialisation de l'application (session, configuration, base de données, autoload, etc.)*/
require_once __DIR__ . '/app/init.php';

/* Chargement automatique des contrôleurs et des modèles */
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/app/';

    /* Charger uniquement les classes depuis App/ */
    if (strncmp($prefix, $class, strlen($prefix)) !== 0) {
        return;
    }

    /* Get relative class name */
    $relative_class = substr($class, strlen($prefix));

    /* Replace namespace separators with directory separators */
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

/* Create the router */
$router = new Router();

/* Handle subfolder */
$basePath = '/eTransactionAPP';
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = rtrim(str_replace($basePath, '', $uri), '/') ?: '/';

/* -------------------- Routes -------------------- */

/* Home page - Produits */
$router->get('/', 'HomeController@index');

/* Login Modal*/
$router->get('/login', function () {
    $controller = new \App\Controllers\LoginController();
    $controller->login();
});
$router->post('/login', 'LoginController@login');

/* Connexion page (protected) */
$router->get('/connexion', function () {
    authMiddleware();
    require __DIR__ . '/app/Views/connexion/index.php';
});

/* Relevé page (protected) */
$router->get('/releve', function () {
    authMiddleware();
    $controller = new \App\Controllers\BankController();
    $controller->index();
});

/* Products page (protected) */
$router->get('/produits', function () {
    authMiddleware();
    $controller = new \App\Controllers\ProductController();
    $controller->index();
});

/* Commandes page (protected) */
$router->get('/commandes', function () {
    authMiddleware();
    $controller = new \App\Controllers\CommandController();
    $controller->index();
});

/* Expedition (protected) */
$router->get('/expedition', function () {
    authMiddleware();
    require __DIR__ . '/app/Views/expedition/index.php';
});

/* Handle form submission */
$router->post('/expeditions/store', 'ExpeditionController@store');

/* Payment (protected) */
$router->get('/payment', function () {
    authMiddleware();
    require __DIR__ . '/app/Views/payment/index.php';
});
$router->post('/payment/process', function () {
    $controller = new \App\Controllers\PaymentController();
    $controller->process();
});

/* Verification */
$router->get('/verification/success', function () {
    $paymentId = $_GET['id'] ?? null;
    require __DIR__ . '/app/Views/verification/index.php';
});

/* Run router */
$router->run($path);

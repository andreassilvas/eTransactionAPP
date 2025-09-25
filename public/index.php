<?php

// Load core files first
require_once __DIR__ . '/../core/Router.php';
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Model.php';

// Load app initialization (session, config, database, autoload, etc.)
require_once __DIR__ . '/../app/init.php';

// Autoload controllers and models
spl_autoload_register(function ($class) {
    $prefix = 'App\\';                  // All classes live under App\
    $base_dir = __DIR__ . '/../app/';   // Root of the app

    // Only load classes from App\
    if (strncmp($prefix, $class, strlen($prefix)) !== 0) {
        return;
    }

    // Get relative class name
    $relative_class = substr($class, strlen($prefix));

    // Replace namespace separators with directory separators
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

// Create the router
$router = new Router();

// Handle subfolder
$basePath = '/eTransactionAPP/public';
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = rtrim(str_replace($basePath, '', $uri), '/') ?: '/';

// -------------------- Routes --------------------

// Home page - Produits
$router->get('/', 'HomeController@index');

// Login
$router->get('/login', function () {
    $controller = new \App\Controllers\LoginController();
    $controller->show();
});
$router->post('/login', 'LoginController@login');

// Expedition
$router->get('/expedition', function () {
    if (!isset($_SESSION['client_id'])) {
        header("Location: /eTransactionAPP/public/");
        exit;
    }
    require __DIR__ . '/../app/views/expedition/index.php';
});
$router->post('/expeditions/store', 'ExpeditionController@store');

// Payment
$router->get('/payment', function () {
    if (!isset($_SESSION['client_id'])) {
        header("Location: /eTransactionAPP/public/");
        exit;
    }
    require __DIR__ . '/../app/views/payment/index.php';
});
$router->post('/payment/process', function () {
    $controller = new \App\Controllers\PaymentController();
    $controller->process();
});

// Verification
$router->get('/verification/success', function () {
    $paymentId = $_GET['id'] ?? null;
    require __DIR__ . '/../app/views/verification/index.php';
});

// -------------------- Run router --------------------
$router->run($path);

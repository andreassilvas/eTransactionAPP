<?php
// Load core files first
require_once "../core/Router.php";
require_once "../core/Controller.php";
require_once "../core/Model.php";

// Autoload controllers and models
spl_autoload_register(function ($class) {
    if (file_exists("../app/controllers/$class.php"))
        require_once "../app/controllers/$class.php";
    elseif (file_exists("../app/models/$class.php"))
        require_once "../app/models/$class.php";
});

// CREATE the router first
$router = new Router();

// Optional: handle subfolder
$basePath = '/eTransactionAPP/public';
$path = str_replace($basePath, '', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// Routes
$router->get('/', function () {
    echo "Welcome to eTransactionAPP <br>";
    echo '<a href="/eTransactionAPP/public/create-account">Créer un compte</a>';
});

$router->get('/create-account', 'CreateAccountController@index');
$router->post('/create-account/store', 'CreateAccountController@store');

$router->get('/', 'HomeController@index');
$router->post('/login', 'AuthController@login');

$router->post('/login', function () {
    require_once __DIR__ . '/../app/views/auth/login_handler.php';
});

$router->get('/expedition', function () {
    session_start();
    if (!isset($_SESSION['client_id'])) {
        header("Location: /eTransactionAPP/public/");
        exit;
    }
    require __DIR__ . '/../app/views/expedition/index.php';
});




// Run the router
$router->run($path);

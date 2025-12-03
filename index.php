<?php

/* Inclure les fichiers principaux */
require_once __DIR__ . '/app/Helpers/AuthHelper.php';
require_once __DIR__ . '/core/Router.php';
require_once __DIR__ . '/core/Controller.php';
require_once __DIR__ . '/core/Model.php';


/* Charger l'initialisation de l'application (session, configuration, base de données, autoload, etc.)*/
require_once __DIR__ . '/app/init.php';

/* Chargement automatique des contrôleurs et des modèles */
spl_autoload_register(function ($class): void {
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
$router->get('/login', function (): void {
    $controller = new \App\Controllers\LoginController();
    $controller->login();
});
$router->post('/login', 'LoginController@login');

/*========= Connexion page (protected) ========================
===============================================================*/
$router->get('/connexion', function (): void {
    authMiddleware();
    require __DIR__ . '/app/Views/connexion/index.php';
});

/* Relevé page (protected) */
$router->get('/releve', function (): void {
    authMiddleware();
    $controller = new \App\Controllers\BankController();
    $controller->index();
});

/* Commandes page (protected) */
$router->get('/commandes', function (): void {
    authMiddleware();
    $controller = new \App\Controllers\CommandController();
    $controller->index();
});

/* Expedition (protected) */
$router->get('/expedition', function (): void {
    authMiddleware();
    require __DIR__ . '/app/Views/expedition/index.php';
});

/* =============== Tableau de Bord page (protected) Dashboard============
========================================================================= */
$router->get('/tableau-de-bord', function (): void {
    authMiddleware();
    $controller = new \App\Controllers\DashboardController();
    $controller->index('dashboard/index.php');
});

/* Voir les Produits en stock page (protected) */
$router->get('/produits-en-stock', function (): void {
    authMiddleware();
    $controller = new \App\Controllers\ProductController();
    $controller->index();
});

/* Voir les Produits livré page (protected) */
$router->get('/produits-livre', function (): void {
    authMiddleware();
    $controller = new \App\Controllers\DashboardController();
    $controller->index('products_delivered/index.php');
});

// /*Ajouter / Modifier un Produit page (protected)*/
// $router->get('/gestion-des-produits', function (): void {
//     authMiddleware();
//     $controller = new \App\Controllers\ProductController();
//     $controller->index();
// });

/* Gestion des Utilisateurs (protected) */
$router->get('/gestion-utilisateurs', function (): void {
    authMiddleware();
    (new \App\Controllers\ClientManagementController())->index();
});

$router->get('/gestion-utilisateurs/list', function () {
    authMiddleware();
    (new \App\Controllers\ClientManagementController())->list();
});
$router->post('/gestion-utilisateurs/store', function () {
    authMiddleware();
    (new \App\Controllers\ClientManagementController())->store();
});
$router->post('/gestion-utilisateurs/update', function () {
    authMiddleware();
    (new \App\Controllers\ClientManagementController())->update();
});
$router->get('/gestion-utilisateurs/delete', function () {
    authMiddleware();
    (new \App\Controllers\ClientManagementController())->delete();
});


/* --- Geo API (used by clientManagement.js dropdowns) --- */
$router->get('/geo/provinces', function (): void {
    authMiddleware(); // remove if you want it public
    (new \App\Controllers\GeoController())->provinces();
});

$router->get('/geo/provinces/cities', function (): void {
    // expects ?code=QC&search=&limit=&offset=
    authMiddleware();
    (new \App\Controllers\GeoController())->citiesByProvince();
});

$router->get('/geo/cities', function (): void {
    // optional: ?province=QC&search=&limit=&offset=
    authMiddleware();
    (new \App\Controllers\GeoController())->cities();
});

$router->get('/geo/cities/show', function (): void {
    // expects ?id=123
    authMiddleware();
    (new \App\Controllers\GeoController())->cityShow();
});



/* Handle form submission */
$router->post('/expeditions/store', 'ExpeditionController@store');

/* Payment (protected) */
$router->get('/payment', function (): void {
    authMiddleware();
    require __DIR__ . '/app/Views/payment/index.php';
});
$router->post('/payment/process', function (): void {
    $controller = new \App\Controllers\PaymentController();
    $controller->process();
});

/* Verification */
$router->get('/verification/success', function (): void {
    $paymentId = $_GET['id'] ?? null;
    require __DIR__ . '/app/Views/verification/index.php';
});

/* Run router */
$router->run($path);

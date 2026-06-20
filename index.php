<?php

use App\Controllers\LoginController;
require_once __DIR__ . '/app/Helpers/AuthHelper.php';
require_once __DIR__ . '/core/Router.php';
require_once __DIR__ . '/core/Controller.php';
require_once __DIR__ . '/core/Model.php';
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


ini_set('display_errors', 1);
error_reporting(E_ALL);

/* -------------------- Routes -------------------- */

/*=============== Home page (protected) ========================
===============================================================*/
$router->get('/', 'HomeController@index');

/* Login Modal*/
$router->get('/login', function (): void {
    $controller = new \App\Controllers\LoginController();
    $controller->login();
});
$router->post('/login', 'LoginController@login');

/*============= Admin page (protected) =====================
===============================================================*/
$router->get('/portal_admin', function (): void {
    authMiddleware('admin');
    require __DIR__ . '/app/Views/admin/index.php';
});

/*============= Banque page (protected) ===============
===============================================================*/
$router->get('/banque', function (): void {
    authMiddleware();
    $controller = new \App\Controllers\BankController();
    $controller->index();
});

/*============= Historique des commandes page (protected) ======
===============================================================*/
$router->get('/commandes', function (): void {
    authMiddleware();
    $controller = new \App\Controllers\CommandController();
    $controller->index();
});

/*============= Transfert de fonds page (protected) ======
===============================================================*/
$router->get('/transferts', function (): void {
    authMiddleware();

    require __DIR__ . '/app/Views/transferts/index.php';
});

/*============= Expédition Client (protected) ==================
===============================================================*/
$router->get('/expedition', function (): void {
    authMiddleware('user');
    require __DIR__ . '/app/Views/expedition/index.php';
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
/*========= Tableau de bord page (protected) Dashboard ============
==================================================================*/
$router->get('/tableau-de-bord', function (): void {
    authMiddleware();
    $controller = new \App\Controllers\DashboardController();
    $controller->index('dashboard/index.php');
});

/* Voir les Produits en stock page (protected) */
$router->get('/produits-en-stock', function (): void {
    authMiddleware();
    $controller = new \App\Controllers\ProductController();
    $controller->index('products/index.php');
});

/* Voir les Produits livré page (protected) */
$router->get('/produits-livre', function (): void {
    authMiddleware();
    $controller = new \App\Controllers\DashboardController();
    $controller->index('products_delivered/index.php');
});

/* Gestion des produits page (protected)*/
$router->get('/administration-des-produits', function (): void {
    authMiddleware();
    $controller = new \App\Controllers\ProductController();
    $controller->index('product_management/index.php');
});

$router->get('/administration-des-produits/list', function () {
    authMiddleware();
    $controller = new \App\Controllers\ProductController();
    $controller->list();
});
$router->post('/administration-des-produits/store', function () {
    authMiddleware();
    $controller = new \App\Controllers\ProductController();
    $controller->store();
});
$router->post('/administration-des-produits/update', function () {
    authMiddleware();
    $controller = new \App\Controllers\ProductController();
    $controller->update();
});
$router->get('/administration-des-produits/delete', function () {
    authMiddleware();
    $controller = new \App\Controllers\ProductController();
    $controller->delete();
});
$router->get('/administration-des-produits/options/category', function () {
    authMiddleware();
    $controller = new \App\Controllers\ProductController();
    $controller->options('category');
});

$router->get('/administration-des-produits/options/brand', function () {
    authMiddleware();
    $controller = new \App\Controllers\ProductController();
    $controller->options('brand');
});

$router->get('/administration-des-produits/options/model', function () {
    authMiddleware();
    $controller = new \App\Controllers\ProductController();
    $controller->options('model');
});

$router->get('/administration-des-produits/options/supplier', function () {
    authMiddleware();
    $controller = new \App\Controllers\ProductController();
    $controller->options('supplier');
});

$router->get('/administration-des-produits/options/warranty_period', function () {
    authMiddleware();
    $controller = new \App\Controllers\ProductController();
    $controller->options('warranty_period');
});

$router->get('/administration-des-produits/options/support_level', function () {
    authMiddleware();
    $controller = new \App\Controllers\ProductController();
    $controller->options('support_level');
});

/*========= Gestion des Utilisateurs page (protected) ============
==================================================================*/
$router->get('/gestion-utilisateurs', function (): void {
    authMiddleware();
    $controller = new \App\Controllers\ClientManagementController();
    $controller->index();
});

$router->get('/gestion-utilisateurs/list', function () {
    authMiddleware();
    $controller = new \App\Controllers\ClientManagementController();
    $controller->list();
});

// List users without cards (for add card form dropdown)
$router->get('/gestion-utilisateurs/sans-cartes', 'ClientManagementController@listWithoutCards');
// List cards (for add card form dropdown)
$router->post('/api/payment/add-card', 'PaymentController@addCardAPI');

$router->post('/gestion-utilisateurs/store', function () {
    authMiddleware();
    $controller = new \App\Controllers\ClientManagementController();
    $controller->store();
});
$router->post('/gestion-utilisateurs/update', function () {
    authMiddleware();
    $controller = new \App\Controllers\ClientManagementController();
    $controller->update();
});
$router->get('/gestion-utilisateurs/delete', function () {
    authMiddleware();
    $controller = new \App\Controllers\ClientManagementController();
    $controller->delete();
});

/* --- Geo API (used by clientManagement.js dropdowns) --- */
$router->get('/geo/provinces', function (): void {
    authMiddleware();
    $controller = new \App\Controllers\GeoController();
    $controller->provinces();
});

$router->get('/geo/provinces/cities', function (): void {
    authMiddleware();
    $controller = new \App\Controllers\GeoController();
    $controller->citiesByProvince();
});

$router->get('/geo/cities', function (): void {
    authMiddleware();
    $controller = new \App\Controllers\GeoController();
    $controller->cities();
});

$router->get('/geo/cities/show', function (): void {
    authMiddleware();
    $controller = new \App\Controllers\GeoController();
    $controller->cityShow();
});
//=================================================================================
//-----------ADD CREDIT CARD---------------------------------------------------------
//=================================================================================
$router->get('/ajouter-carte', function (): void {
    authMiddleware();
    require __DIR__ . '/app/Views/addCard/index.php';
});

//=================================================================================
//-----------DOCUMENTATION---------------------------------------------------------
//=================================================================================
$router->get('/documentation', function (): void {
    require __DIR__ . '/app/Views/documentation/index.php';
});

//=================================================================================
//-----------LOGIN-----------------------------------------------------------------
//=================================================================================
//Route de connexion du backend pour les requêtes AJAX
$router->post('/api/login', 'LoginController@loginAPI');

require_once __DIR__ . '/app/middleware/apiAuth.php';

$router->get('/api/test', function () {
    apiAuth();

    echo json_encode([
        'status' => 'success',
        'message' => 'You are authenticated'
    ]);
});

//=================================================================================
//-----------LOGGED-IN USER--------------------------------------------------------
//=================================================================================
$router->get('/api/currentuser', 'ClientManagementController@currentUser');

//=================================================================================
//-----------ADMIN PRODUCTS--------------------------------------------------------------
//=================================================================================
$router->get('/api/products', 'ProductController@getProductsAPI');

//=================================================================================
//-----------USER CART------------------------------------------------------------------
//=================================================================================
$router->post('/api/cart/add', 'CartController@add');
$router->get('/api/cart', 'CartController@get');
$router->post('/api/cart/remove', 'CartController@remove');
$router->post('/api/cart/update', 'CartController@update');
$router->post('/api/cart/clear', 'CartController@clear');

//=================================================================================
//-----------USER PAYMENT---------------------------------------------------------------
//=================================================================================
$router->post('/api/payment', 'PaymentController@paymentAPI');
$router->get('/api/payment', 'PaymentController@paymentAPI');

//-------Verification success {id}
$router->get('/api/payment/details', 'PaymentController@getPaymentDetailsAPI');


//=================================================================================
//-----------USER EXPEDITION------------------------------------------------------------
//=================================================================================
$router->get('/api/expedition', 'ExpeditionController@expeditionsAPI');

//-------Expeditions Details {id}
$router->get('/api/expedition/details', 'ExpeditionController@expeditionDetailsAPI');

//=================================================================================
//-----------ADMIN Transferts de fonds--------------------------------------------------
//=================================================================================
$router->post('/api/transferts', 'TransfertController@transfertsAPI');

//=================================================================================
//-----------LOGOUT----------------------------------------------------------------
//=================================================================================
$router->post('/api/logout', 'LoginController@logoutAPI');

//TEST Routes
// var_dump($path);
// exit;

/* Run router */
$router->run($path);


<?php
namespace App\Controllers;

use App\Models\Products;

/**
 * Class ProductController
 *
 * Contrôleur responsable de la gestion des produits.
 * - Affiche la liste des produits.
 * - Permet d'ajouter un nouveau produit.
 *
 * @package App\Controllers
 */

class ProductController
{
    /**
     * Affiche la liste de tous les produits.
     *
     * Récupère tous les produits depuis le modèle `Products`
     * et les transmet à la vue correspondante.
     *
     * @return void
     */
    public function index()
    {
        $productModel = new Products();

        // Récupère tous les produits
        $products = $productModel->all();

        // Charge la vue des produits et lui transmet les données
        require __DIR__ . '/../Views/products/index.php';

        // Debugging line
        // echo "<pre>";
        // print_r($products);
        // echo "</pre>";
        // error_log(print_r($products, true));
    }

    /**
     * Ajoute un nouveau produit.
     *
     * Récupère les données du formulaire POST, les transmet au modèle
     * pour création en base de données et redirige vers la liste des produits.
     *
     * @return void
     */
    public function store()
    {
        $productModel = new Products();
        // Prépare les données à insérer
        $data = [
            ':name' => $_POST['name'],
            ':category' => $_POST['category'],
            ':brand' => $_POST['brand'],
            ':model' => $_POST['model'],
            ':specs' => $_POST['specs'],
            ':price' => $_POST['price'],
            ':stock' => $_POST['stock'],
            ':warranty_period' => $_POST['warranty_period'],
            ':support_level' => $_POST['support_level'],
            ':supplier' => $_POST['supplier']
        ];

        // Création du produit en base de données
        $productModel->create($data);

        // Redirection vers la liste des produits
        header('Location: ' . BASE_URL . '/products');
        exit;
    }

}

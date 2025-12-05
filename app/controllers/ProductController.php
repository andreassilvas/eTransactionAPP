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
    public function index(string $view = '/products/index.php')
    {
        $productModel = new Products();

        // Récupère tous les produits
        $products = $productModel->all();
        $stockSummary = $productModel->getStockSummary();

        // Charge la vue des produits et lui transmet les données
        require __DIR__ . '/../Views/' . $view;

        // Debugging line
        // echo "<pre>";
        // print_r($products);
        // echo "</pre>";
        // error_log(print_r($products, true));

        // print_r($productModel->all());

    }

    public function list()
    {
        $productModel = new Products();
        $products = $productModel->all();
        header('Content-Type: application/json');

        echo json_encode($products);
        exit;
    }

    public function update()
    {
        // Read JSON sent from fetch()
        $payload = json_decode(file_get_contents('php://input'), true);

        if (!$payload || !isset($payload['id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'ID manquant']);
            return;
        }

        $id = (int) $payload['id'];
        unset($payload['id']); // remove ID from data array

        $productModel = new Products();

        $ok = $productModel->update($id, $payload);

        echo json_encode(['success' => $ok]);
    }

    public function delete()
    {
        if (!isset($_GET['id'])) {
            echo json_encode(['error' => 'ID missing']);
            http_response_code(400);
            return;
        }

        $id = (int) $_GET['id'];
        $productModel = new Products();

        $ok = $productModel->delete($id);

        echo json_encode(['success' => $ok]);
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
        $data = $this->payload(); // <-- supports both JSON & form POST

        // Validate required fields
        foreach (['name', 'category', 'brand', 'model', 'specs_desc', 'price', 'stock', 'warranty_period', 'support_level', 'supplier'] as $k) {
            if (empty($data[$k])) {
                http_response_code(422);
                echo json_encode(['ok' => false, 'error' => "Missing $k"]);
                return;
            }
        }

        $productModel = new Products();
        $id = $productModel->create([
            ':name' => $data['name'],
            ':category' => $data['category'],
            ':brand' => $data['brand'],
            ':model' => $data['model'],
            ':specs_desc' => $data['specs_desc'],
            ':price' => $data['price'],
            ':stock' => $data['stock'],
            ':warranty_period' => $data['warranty_period'],
            ':support_level' => $data['support_level'],
            ':supplier' => $data['supplier'],
        ]);

        $newProduct = $productModel->find($id);
        header('Content-Type: application/json');
        echo json_encode($newProduct);

        error_log("Payload: " . print_r($data, true));
        error_log("Creating product...");
        error_log("Created product ID: $id");
        $newProduct = $productModel->find($id);
        error_log("New Product: " . print_r($newProduct, true));
    }

    public function options($type)
    {
        $allowed = ['category', 'brand', 'model', 'warranty_period', 'support_level', 'supplier'];

        // Is the column allowed?
        if (!in_array($type, $allowed)) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid option type']);
            return;
        }

        $productModel = new Products();
        $options = $productModel->getDistinctOptions($type);

        header('Content-Type: application/json');
        echo json_encode($options);
    }

    /* ---------- helpers ---------- */
    private function payload()
    {
        $raw = file_get_contents('php://input');
        return $raw ? (array) json_decode($raw, true) : $_POST;
    }
}

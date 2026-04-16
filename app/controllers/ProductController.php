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
    /* ---------- helpers ---------- */
    private function payload()
    {
        $raw = file_get_contents('php://input');
        return $raw ? (array) json_decode($raw, true) : $_POST;
    }

    private function getAllProducts()
    {
        $productModel = new Products();
        return $productModel->all();
    }

    private function json($data)
    {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    /* ---------- Products API ---------- */
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
        $products = $this->getAllProducts();
        $stockSummary = $productModel->getStockSummary();

        // Charge la vue des produits et lui transmet les données
        require __DIR__ . '/../Views/' . $view;
    }

    public function list()
    {
        $products = $this->getAllProducts();
        $this->json($products);
        exit;
    }

    public function update()
    {
        // Read JSON sent from fetch()
        $payload = json_decode(file_get_contents('php://input'), true);

        if (!$payload || !isset($payload['id'])) {
            http_response_code(400);
            $this->json(['error' => 'ID manquant']);
            return;
        }

        $id = (int) $payload['id'];
        unset($payload['id']); // remove ID from data array

        $productModel = new Products();

        $ok = $productModel->update($id, $payload);

        $this->json(['success' => $ok]);
    }

    public function delete()
    {
        if (!isset($_GET['id'])) {
            $this->json(['error' => 'ID missing']);

            http_response_code(400);
            return;
        }

        $id = (int) $_GET['id'];
        $productModel = new Products();

        $delete = $productModel->delete($id);

        $this->json(['success' => $delete ? 'Product deleted' : 'Failed to delete']);
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
        $this->json($newProduct);

        error_log("Payload: " . print_r($data, true));
        error_log("Creating product...");
        error_log("Created product ID: $id");

        $newProduct = $productModel->find($id);
        error_log("New Product: " . print_r($newProduct, true));
    }

    public function options($type)
    {
        $allowed = ['category', 'brand', 'model', 'warranty_period', 'support_level', 'supplier'];

        if (!in_array($type, $allowed)) {
            http_response_code(400);
            $this->json(['error' => 'Invalid option type']);
            return;
        }

        $productModel = new Products();
        $options = $productModel->getDistinctOptions($type);

        $this->json($options);
    }


    public function getProductsAPI()
    {
        //Protected route
        require_once __DIR__ . '/../middleware/apiAuth.php';
        apiAuth();

        //Get products
        $products = $this->getAllProducts();

        $this->json([
            'success' => true,
            'data' => $products
        ]);
    }
}

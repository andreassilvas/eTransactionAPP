<?php
namespace App\Controllers;

use App\Models\Products;

class ProductController
{
    public function index()
    {
        $productModel = new Products();
        $products = $productModel->all();
        require __DIR__ . '/../views/products/index.php';

        // Debugging line
        // echo "<pre>";
        // print_r($products);
        // echo "</pre>";
        // error_log(print_r($products, true));
    }
    public function store()
    {
        $productModel = new Products();
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

        $productModel->create($data);
        header('Location: /products');
        exit;
    }

}

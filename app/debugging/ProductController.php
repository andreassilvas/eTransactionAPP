<?php
namespace App\Controllers;

use App\Models\Products;

class ProductController
{
    public function index()
    {
        $productModel = new Products();
        $products = $productModel->all();

        echo "<h2>Debug Products</h2>";
        echo "<pre>";
        print_r($products);
        echo "</pre>";
        die();
    }
}

<?php
namespace App\Controllers;
use Core\Controller;

use App\Models\Products;
class HomeController extends Controller
{
    public function index()
    {
        $_SESSION['cart'] = []; // reset every load (only for testing)

        $productModel = new Products();
        $products = $productModel->all();

        $this->view('home/index', ['products' => $products]);
    }
}

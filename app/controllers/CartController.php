<?php

namespace App\Controllers;

class CartController
{
    //Helper pour envoyer une réponse JSON
    private function json($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    private function initCart()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }

    public function add()
    {
        $this->initCart();

        $data = json_decode(file_get_contents('php://input'), true);
        $productId = $data['product_id'] ?? null;

        if (!$productId) {
            $this->json([
                'status' => 'error',
                'message' => 'Product ID is required'
            ], 400);
        }

        //If exists -> increment
        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId] += 1;
        } else {
            $_SESSION['cart'][$productId] = 1;
        }

        $this->json([
            'status' => 'success',
            'cart' => $_SESSION['cart']
        ]);
    }

    public function update()
    {
        $this->initCart();

        $data = json_decode(file_get_contents('php://input'), true);

        $productId = $data['product_id'] ?? null;
        $quantity = $data['quantity'] ?? null;

        if (!$productId || $quantity === null) {
            $this->json([
                'status' => 'error',
                'message' => 'Product ID and quantity required'
            ], 400);
        }

        $_SESSION['cart'][$productId] = $quantity;

        $this->json([
            'status' => 'success',
            'cart' => $_SESSION['cart']
        ]);
    }
    public function get()
    {

        $this->initCart();

        $productModel = new \App\Models\Products();
        $products = $productModel->all();

        $cartDetailed = [];

        foreach ($_SESSION['cart'] as $productId => $quantity) {
            foreach ($products as $product) {
                if ($product['id'] == $productId) {
                    $cartDetailed[] = [
                        'id' => $product['id'],
                        'name' => $product['name'],
                        'price' => $product['price'],
                        'quantity' => $quantity
                    ];
                }
            }
        }

        $this->json(
            [
                'status' => 'success',
                'cart' => $cartDetailed
            ],
        );
    }
    public function remove()
    {
        $this->initCart();

        $data = json_decode(file_get_contents('php://input'), true);
        $productId = isset($data['product_id']) ? (int) $data['product_id'] : null;

        if ($productId !== null && isset($_SESSION['cart'][$productId])) {
            unset($_SESSION['cart'][$productId]);
        }

        $this->json([
            'status' => 'success',
            'cart' => $_SESSION['cart']
        ]);
    }

    public function clear()
    {
        $this->initCart();

        unset($_SESSION['cart']); // remove cart completely

        $this->json([
            'status' => 'success',
            'cart' => []
        ]);
    }
}
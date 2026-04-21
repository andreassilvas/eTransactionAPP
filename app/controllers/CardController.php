<?php

namespace App\Controllers;

class CardController
{
    //Helper pour envoyer une réponse JSON
    private function json($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    private function initCard()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['card'])) {
            $_SESSION['card'] = [];
        }
    }

    public function add()
    {
        require_once __DIR__ . '/../middleware/apiAuth.php';
        apiAuth();

        $this->initCard();

        $data = json_decode(file_get_contents('php://input'), true);
        $productId = $data['product_id'] ?? null;

        if (!$productId) {
            $this->json([
                'status' => 'error',
                'message' => 'Product ID is required'
            ], 400);
        }

        $_SESSION['card'][] = $productId;

        $this->json([
            'status' => 'success',
            'card' => $_SESSION['card']
        ]);
    }

    public function get()
    {
        require_once __DIR__ . '/../middleware/apiAuth.php';
        apiAuth();

        $this->initCard();

        $this->json([
            'status' => 'success',
            'card' => $_SESSION['card']
        ]);
    }

    public function remove()
    {
        require_once __DIR__ . '/../middleware/apiAuth.php';
        apiAuth();

        $this->initCard();

        $data = json_decode(file_get_contents('php://input'), true);
        $productId = $data['product_id'] ?? null;


        $_SESSION['card'] = array_filter(
            $_SESSION['card'],
            fn($id) => $id != $productId
        );

        $this->json([
            'status' => 'success',
            'card' => array_values($_SESSION['card'])
        ]);
    }
}
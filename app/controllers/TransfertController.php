<?php

namespace App\Controllers;

use App\Models\Database;
use App\Models\BankTransaction;

class TransfertController
{
    private function json($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    public function transfertsAPI()
    {
        require_once __DIR__ . '/../middleware/apiAuth.php';

        apiAuth();

        try {

            $payload = json_decode(
                file_get_contents('php://input'),
                true
            );

            if (json_last_error() !== JSON_ERROR_NONE) {

                $this->json([
                    'status' => 'error',
                    'message' => 'JSON invalide'
                ], 400);
            }

            $toClientId = (int) ($payload['to_client_id'] ?? 0);

            $amount = (float) ($payload['amount'] ?? 0);

            $description = trim($payload['description'] ?? '');

            if ($toClientId <= 0 || $amount <= 0) {

                $this->json([
                    'status' => 'error',
                    'message' => 'Informations invalides'
                ], 400);
            }

            // Validate target client
            $clientModel = new \App\Models\Client();

            $client = $clientModel->findById($toClientId);

            if (!$client) {

                $this->json([
                    'status' => 'error',
                    'message' => 'Client introuvable'
                ], 404);
            }

            $db = Database::getConnection();

            $transactionModel = new BankTransaction($db);

            $result = $transactionModel->deposit(
                $toClientId,
                $amount,
                $description
            );

            $this->json([
                'status' => 'success',
                'message' => 'Dépôt effectué avec succès',
                'data' => $result
            ], 201);

        } catch (\Exception $e) {

            $this->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
<?php

namespace App\Controllers;

use App\Models\Database;
use App\Models\BankTransaction;
use App\Models\Client;

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

        $userModel = new Client();

        $currentUser = $userModel->findById(
            $_REQUEST['user']['id']
        );

        $companyId = $currentUser['company_id'];

        $userId = $currentUser['id'];

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

            $amount = (float) ($payload['amount'] ?? 0);

            $description = trim($payload['description'] ?? '');

            $transactionType =
                trim($payload['operation_type'] ?? '');

            if ($amount <= 0 || empty($transactionType)) {

                $this->json([
                    'status' => 'error',
                    'message' => 'Informations invalides'
                ], 400);
            }

            $db = Database::getConnection();

            $transactionModel = new BankTransaction($db);

            $result = $transactionModel->deposit(
                $companyId,
                $userId,
                $amount,
                $transactionType,
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
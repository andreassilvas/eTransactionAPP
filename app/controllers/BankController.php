<?php
namespace App\Controllers;

use App\Models\BankTransaction;
use App\Models\Database;

class BankController
{
    public function index()
    {
        // Start session only if not started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['client_id'])) {
            header("Location: /eTransactionAPP/public/");
            exit;
        }

        $clientId = $_SESSION['client_id'];

        try {
            $db = Database::getConnection();
            $transactionModel = new BankTransaction($db);
            $transactions = $transactionModel->getByClientId($clientId);

            // check if data is fetched
            // var_dump($transactions); exit;

        } catch (\PDOException $e) {
            die("DB Error: " . $e->getMessage());
        }

        // Pass $transactions to the view
        require __DIR__ . '/../views/releve/index.php';
    }
}

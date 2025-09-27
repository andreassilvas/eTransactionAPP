<?php
namespace App\Controllers;

use App\Models\Client;
use App\Models\Expedition;
use App\Models\Payment;
use App\Models\BankTransaction;
use App\Models\Database;
use PDO;

class PaymentController
{
    public function process()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Check expedition data exists
        if (!isset($_SESSION['expedition_data'])) {
            header("Location: /eTransactionAPP/public/expedition");
            exit;
        }

        // Check logged-in client
        if (!isset($_SESSION['client_id'])) {
            $_SESSION['payment_error'] = "Échec du paiement : Utilisateur non connecté.";
            header("Location: /eTransactionAPP/public/payment");
            exit;
        }

        $clientId = $_SESSION['client_id'];
        $expeditionData = $_SESSION['expedition_data'];
        $amount = floatval($_POST['amount'] ?? 2989.86);
        $cardNumber = $_POST['card_number'] ?? '';
        $expiryMonth = $_POST['expiry_month'] ?? '';
        $expiryYear = $_POST['expiry_year'] ?? '';
        $cvv = $_POST['cvv'] ?? '';

        $paymentSuccess = false;
        $errorMessage = '';

        // --- Card validation ---
        if (empty($cardNumber) || empty($expiryMonth) || empty($expiryYear) || empty($cvv)) {
            $errorMessage = "Veuillez remplir tous les champs.";
        } elseif (intval(substr($cardNumber, -1)) % 2 !== 0) {
            $errorMessage = "La carte est refusée par la banque.";
        } elseif (
            intval($expiryYear) < intval(date('Y')) ||
            (intval($expiryYear) === intval(date('Y')) && intval($expiryMonth) < intval(date('m')))
        ) {
            $errorMessage = "La carte est expirée.";
        } elseif (strlen($cvv) !== 3) {
            $errorMessage = "CVV invalide.";
        } else {
            $paymentSuccess = true;
        }

        if (!$paymentSuccess) {
            $_SESSION['payment_error'] = $errorMessage ?: "Le paiement a échoué.";
            header("Location: /eTransactionAPP/public/payment");
            exit;
        }

        $db = Database::getConnection();

        try {
            $db->beginTransaction();

            // Fetch client from DB using client_id
            $clientModel = new Client();
            $client = $clientModel->findById($clientId);

            if (!$client) {
                throw new \Exception("Client introuvable.");
            }

            // Check available balance
            $transactionModel = new BankTransaction($db);
            $transactions = $transactionModel->getByClientId($clientId);
            $currentBalance = $transactions[0]['balance'] ?? 0;

            if ($currentBalance < $amount) {
                throw new \Exception("Fonds insuffisants.");
            }

            // Save expedition (shipping info can differ)
            $expeditionModel = new Expedition();
            $expeditionId = $expeditionModel->create([
                'client_id' => $clientId,
                'ship_email' => $expeditionData['email'],
                'ship_address' => $expeditionData['address'],
                'ship_city' => $expeditionData['city'],
                'ship_province' => $expeditionData['province'],
                'ship_postcode' => $expeditionData['postcode'],
                'status' => 'paid',
                'date' => date('Y-m-d')
            ]);

            // Save payment record
            $paymentModel = new Payment();
            $paymentId = $paymentModel->create([
                'expedition_id' => $expeditionId,
                'amount' => $amount,
                'status' => 'completed',
                'last4' => substr($cardNumber, -4)
            ]);

            // Debit client account
            $newBalance = $currentBalance - $amount;
            $transactionModel->addTransaction(
                $clientId,
                "Payment for expedition #$expeditionId",
                0.00,
                $amount,
                $newBalance
            );

            $db->commit();
            unset($_SESSION['expedition_data']);

            header("Location: /eTransactionAPP/public/verification/success?id=$paymentId");
            exit;

        } catch (\Exception $e) {
            $db->rollBack();
            $_SESSION['payment_error'] = "Échec du paiement : " . $e->getMessage();
            header("Location: /eTransactionAPP/public/payment");
            exit;
        }
    }
}

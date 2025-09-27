<?php
namespace App\Controllers;

use App\Models\Client;
use App\Models\Expedition;
use App\Models\Payment;
use App\Models\BankTransaction;
use App\Models\PaymentValidation;
use App\Models\Database;

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
        $cardNumber = str_replace(' ', '', $_POST['card_number'] ?? '');
        $expiryMonth = $_POST['expiry_month'] ?? '';
        $expiryYear = $_POST['expiry_year'] ?? '';
        $cvv = $_POST['cvv'] ?? '';

        // --- Card validation against payment_validation table ---
        $paymentValidationModel = new PaymentValidation();
        $cardValid = $paymentValidationModel->findValidCard(
            $clientId,
            $cardNumber,
            $expiryMonth,
            $expiryYear,
            $cvv
        );

        if (!$cardValid) {
            $_SESSION['payment_error'] = "La carte n'est pas valide ou n'existe pas.";
            header("Location: /eTransactionAPP/public/payment");
            exit;
        }

        // Extra check: card expiry
        if (
            intval($expiryYear) < intval(date('Y')) ||
            (intval($expiryYear) === intval(date('Y')) && intval($expiryMonth) < intval(date('m')))
        ) {
            $_SESSION['payment_error'] = "La carte est expirée.";
            header("Location: /eTransactionAPP/public/payment");
            exit;
        }

        $db = Database::getConnection();

        try {
            $db->beginTransaction();

            // Fetch client from DB
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

            // Save expedition
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
            // Save payment record using validated card info
            $paymentId = $paymentModel->create([
                'expedition_id' => $expeditionId,
                'client_id' => $clientId,
                'amount' => $amount,
                'status' => 'completed',
                'last4' => substr($cardValid['card_number'], -4), // last 4 from validation table
                'method' => $cardValid['card_type'] ?? 'Visa'      // card type from validation table
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

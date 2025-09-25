<?php
namespace App\Controllers;

use App\Models\Client;
use App\Models\Expedition;
use App\Models\Payment;


class PaymentController
{
    public function process()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['expedition_data'])) {
            header("Location: /eTransactionAPP/public/expedition");
            exit;
        }

        $expeditionData = $_SESSION['expedition_data'];
        $amount = floatval($_POST['amount'] ?? 2989.86);
        $cardNumber = $_POST['card_number'] ?? '';
        $expiryMonth = $_POST['expiry_month'] ?? '';
        $expiryYear = $_POST['expiry_year'] ?? '';
        $cvv = $_POST['cvv'] ?? '';

        // --- Payment validation simulation ---
        $paymentSuccess = false;
        $errorMessage = '';

        if (empty($cardNumber) || empty($expiryMonth) || empty($expiryYear) || empty($cvv)) {
            $errorMessage = "Veuillez remplir tous les champs.";
        } elseif (intval(substr($cardNumber, -1)) % 2 !== 0) {
            // Last digit must be even
            $errorMessage = "La carte est refusée par la banque.";
        } elseif (
            intval($expiryYear) < intval(date('Y')) ||
            (intval($expiryYear) === intval(date('Y')) && intval($expiryMonth) < intval(date('m')))
        ) {
            $errorMessage = "La carte est expirée.";
        } elseif (strlen($cvv) !== 3) {
            $errorMessage = "CVV invalide.";
        } elseif ($amount > 5000) {
            // simulate insufficient funds
            $errorMessage = "Fonds insuffisants.";
        } else {
            $paymentSuccess = true;
        }

        if ($paymentSuccess) {
            // 1. Save client
            $clientModel = new Client();
            $client = $clientModel->findByEmailOrPhone($expeditionData['email'], $expeditionData['phone']);
            $clientId = $client
                ? $client['id']
                : $clientModel->create($expeditionData);

            // 2. Save expedition
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

            // 3. Save payment record
            $paymentModel = new Payment();
            $paymentId = $paymentModel->create([
                'expedition_id' => $expeditionId,
                'amount' => $amount,
                'status' => 'completed'
            ]);

            unset($_SESSION['expedition_data']); // clear

            // 4. Redirect to success page
            header("Location: /eTransactionAPP/public/verification/success?id=$paymentId");
            exit;
        } else {
            // Payment failed → save error message in session
            $_SESSION['payment_error'] = $errorMessage ?: "Le paiement a échoué.";
            header("Location: /eTransactionAPP/public/payment");
            exit;
        }
    }
}

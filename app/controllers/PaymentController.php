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

        $paymentSuccess = false;
        $errorMessage = '';

        //Simulation validation carte
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

        if ($paymentSuccess) {
            $db = Database::getConnection();

            try {
                $db->beginTransaction();

                //Save or fetch client
                $clientModel = new Client();
                $client = $clientModel->findByEmailOrPhone($expeditionData['email'], $expeditionData['phone']);
                $clientId = $client ? $client['id'] : $clientModel->create($expeditionData);

                //Vérifier solde disponible
                $transactionModel = new BankTransaction($db);
                $transactions = $transactionModel->getByClientId($clientId);
                $currentBalance = $transactions[0]['balance'] ?? 0;

                if ($currentBalance < $amount) {
                    throw new \Exception("Fonds insuffisants.");
                }

                //Save expedition
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

                //Save payment record
                $paymentModel = new Payment();
                $paymentId = $paymentModel->create([
                    'expedition_id' => $expeditionId,
                    'amount' => $amount,
                    'status' => 'completed',
                    'last4' => substr($cardNumber, -4)
                ]);

                //Débiter le compte client
                $newBalance = $currentBalance - $amount;
                $transactionModel->addTransaction($clientId, "Payment for expedition #$expeditionId", 0.00, $amount, $newBalance);

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
        } else {
            $_SESSION['payment_error'] = $errorMessage ?: "Le paiement a échoué.";
            header("Location: /eTransactionAPP/public/payment");
            exit;
        }
    }
}

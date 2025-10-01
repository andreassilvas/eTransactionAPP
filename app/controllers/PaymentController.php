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
        // --- Vérif expedition ---
        if (!isset($_SESSION['expedition_data'])) {
            header("Location: /eTransactionAPP/public/expedition");
            exit;
        }

        // --- Vérif login client ---
        if (!isset($_SESSION['client_id'])) {
            $_SESSION['payment_error'] = "Échec du paiement : Utilisateur non connecté.";
            header("Location: /eTransactionAPP/public/payment");
            exit;
        }

        $clientId = $_SESSION['client_id'];
        $expeditionData = $_SESSION['expedition_data'];

        // --- Nettoyage des inputs ---
        $amount = floatval($_POST['amount'] ?? 2989.86);
        $cardName = trim($_POST['card_name'] ?? '');
        $cardNumber = trim($_POST['card_number'] ?? '');
        $codePostal = trim($_POST['postcode'] ?? '');
        $expiryDate = trim($_POST['expiry_date'] ?? '');
        $cvv = trim($_POST['cvv'] ?? '');

        // --- Étape 1 : Validation de format ---
        $errors = [];

        // Numéro carte (4 groupes de 4 chiffres séparés par espace)
        if (!preg_match('/^\d{4}\s\d{4}\s\d{4}\s\d{4}$/', $cardNumber)) {
            $errors[] = "Numéro de carte invalide (format attendu : 1234 5678 9123 4567).";
        }

        // Nom titulaire
        if (!preg_match('/^[A-Za-zÀ-ÿ\s]{2,50}$/u', $cardName)) {
            $errors[] = "Nom du titulaire invalide.";
        }

        // Code postal canadien
        if (!preg_match('/^[A-Za-z]\d[A-Za-z]\s\d[A-Za-z]\d$/', $codePostal)) {
            $errors[] = "Code postal invalide (format attendu : A1A 1A1).";
        }

        // Expiration
        if (!preg_match('/^(0[1-9]|1[0-2])\/\d{2}$/', $expiryDate)) {
            $errors[] = "La date d’expiration doit être au format MM/AA.";
        } else {
            [$expMonth, $expYearShort] = explode('/', $expiryDate);
            $expYear = intval("20" . $expYearShort);
            $expMonth = intval($expMonth);

            if ($expYear < date('Y') || ($expYear == date('Y') && $expMonth < date('m'))) {
                $errors[] = "La carte est expirée.";
            }
        }

        // CVV
        if (!preg_match('/^\d{3,4}$/', $cvv)) {
            $errors[] = "CVV invalide.";
        }

        // Retour si erreurs
        if (!empty($errors)) {
            $_SESSION['payment_error'] = implode(" - ", $errors);
            header("Location: /eTransactionAPP/public/payment");
            exit;
        }

        // --- Étape 2 : Validation en base ---
        $paymentValidationModel = new PaymentValidation();
        $cardValid = $paymentValidationModel->findValidCard(
            $clientId,
            $cardName,
            $cardNumber,
            $codePostal,
            $expiryDate,
            $cvv
        );

        if (!$cardValid) {
            $_SESSION['payment_error'] = "Les informations de carte ne correspondent pas à nos dossiers.";
            header("Location: /eTransactionAPP/public/payment");
            exit;
        }

        // --- Étape 3 : Transaction ---
        $db = Database::getConnection();

        try {
            $db->beginTransaction();

            // Vérif client
            $clientModel = new Client();
            $client = $clientModel->findById($clientId);
            if (!$client) {
                throw new \Exception("Client introuvable.");
            }

            // Vérif solde
            $transactionModel = new BankTransaction($db);
            $transactions = $transactionModel->getByClientId($clientId);
            $currentBalance = $transactions[0]['balance'] ?? 0;

            if ($currentBalance < $amount) {
                throw new \Exception("Fonds insuffisants.");
            }

            // Sauvegarde expedition
            $expeditionModel = new Expedition();
            $expeditionId = $expeditionModel->create([
                'client_id' => $clientId,
                'ship_name' => $expeditionData['name'],
                'ship_lastname' => $expeditionData['lastname'],
                'ship_email' => $expeditionData['email'],
                'ship_address' => $expeditionData['address'],
                'ship_city' => $expeditionData['city'],
                'ship_province' => $expeditionData['province'],
                'ship_postcode' => $expeditionData['postcode'],
                'ship_phone' => $expeditionData['phone'],
                'status' => 'paid',
                'date' => date('Y-m-d')
            ]);

            // Sauvegarde paiement
            $paymentModel = new Payment();
            $paymentId = $paymentModel->create([
                'expedition_id' => $expeditionId,
                'client_id' => $clientId,
                'amount' => $amount,
                'status' => 'completed',
                'last4' => substr(str_replace(' ', '', $cardValid['card_number']), -4),
                'method' => $cardValid['card_type'] ?? 'Carte'
            ]);

            // Débit compte client
            $newBalance = $currentBalance - $amount;
            $transactionModel->addTransaction(
                $clientId,
                "Paiement pour l’expédition #$expeditionId",
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

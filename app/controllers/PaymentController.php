<?php
namespace App\Controllers;

use App\Models\Client;
use App\Models\Expedition;
use App\Models\Payment;
use App\Models\BankTransaction;
use App\Models\PaymentValidation;
use App\Models\ExpeditionItem;
use App\Models\Database;
use App\Models\Products;

/**
 * Class PaymentController
 *
 * Contrôleur responsable du traitement des paiements des clients.
 * - Vérifie les informations d'expédition et la connexion du client.
 * - Valide les produits, le stock et les informations de carte.
 * - Effectue les transactions bancaires, crée l'expédition et le paiement.
 *
 * @package App\Controllers
 */

class PaymentController
{
    /**
     * Traite le paiement pour les produits sélectionnés.
     *
     * Étapes principales :
     * 1. Vérifie la session et les informations d'expédition.
     * 2. Vérifie la connexion du client.
     * 3. Valide les produits et le stock disponible.
     * 4. Collecte et valide les informations de carte.
     * 5. Vérifie la carte dans la base de données.
     * 6. Crée l'expédition, les items, le paiement et enregistre la transaction bancaire.
     *
     * @return void
     */
    public function process()
    {
        // Étape 1 : Vérifie que les données d'expédition sont présentes
        if (!isset($_SESSION['expedition_data'])) {
            header("Location: " . BASE_URL . '/expedition');
            exit;
        }

        // Étape 2 : Vérifie que le client est connecté
        if (!isset($_SESSION['client_id'])) {
            $_SESSION['payment_error'] = "Échec du paiement : Utilisateur non connecté.";
            header("Location: " . BASE_URL . '/payment');
            exit;
        }

        $clientId = $_SESSION['client_id'];
        $expeditionData = $_SESSION['expedition_data'];

        // Produit(s) à acheter codé(s) en dur pour l'instant
        $products = [
            ['id' => 7, 'quantity' => 1],
        ];

        // Calculer le montant total
        $productModel = new Products();
        $expeditionItemModel = new ExpeditionItem();

        // Étape 3 : Vérification du stock pour chaque produit
        foreach ($products as $item) {
            $prod = $productModel->find($item['id']);
            if (!$prod) {
                $_SESSION['payment_error'] = "Produit introuvable : ID {$item['id']}";
                header("Location: " . BASE_URL . '/payment');
                exit;
            }

            if ($prod['stock'] < $item['quantity']) {
                $_SESSION['payment_error'] = "Produit en rupture de stock : {$prod['name']}";
                header("Location: " . BASE_URL . '/payment');
                exit;
            }
        }

        // Calcul du montant total
        $totalAmount = 0;
        foreach ($products as $item) {
            $prod = $productModel->find($item['id']);
            $totalAmount += $prod['price'] * $item['quantity'];
        }


        // Étape 4 : Récupération des informations de carte de crédit
        $cardName = trim($_POST['card_name'] ?? '');
        $cardNumber = trim($_POST['card_number'] ?? '');
        $codePostal = trim($_POST['postcode'] ?? '');
        $expiryDate = trim($_POST['expiry_date'] ?? '');
        $cvv = trim($_POST['cvv'] ?? '');

        // Étape 5 : Validation côté serveur des informations de carte
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

        // Retour en cas d'erreurs de validation
        if (!empty($errors)) {
            $_SESSION['payment_error'] = implode(" - ", $errors);
            header("Location: " . BASE_URL . '/payment');
            exit;
        }

        // Étape 6 : Vérification des informations de carte dans la base
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
            header("Location: " . BASE_URL . '/payment');
            exit;
        }

        // Étape 7 : Traitement de la transaction
        $db = Database::getConnection();

        try {
            $db->beginTransaction();

            // Vérification du client
            $clientModel = new Client();
            $client = $clientModel->findById($clientId);
            if (!$client) {
                throw new \Exception("Client introuvable.");
            }

            // Vérification du solde disponible
            $transactionModel = new BankTransaction($db);
            $transactions = $transactionModel->getByClientId($clientId);
            $currentBalance = $transactions[0]['balance'] ?? 0;

            if ($currentBalance < $totalAmount) {
                throw new \Exception("Fonds insuffisants.");
            }

            // Création de l'expédition
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
                'status' => 'success',
                'date' => date('Y-m-d')
            ]);

            // Création des items et mise à jour du stock
            foreach ($products as $item) {
                $prod = $productModel->find($item['id']);
                if (!$prod)
                    continue;

                $expeditionItemModel->create([
                    'expedition_id' => $expeditionId,
                    'product_id' => $prod['id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $prod['price']
                ]);

                $newStock = max(0, $prod['stock'] - $item['quantity']);
                $productModel->update($prod['id'], ['stock' => $newStock]);
            }

            // Créer le paiement
            $paymentModel = new Payment();
            $paymentId = $paymentModel->create([
                'expedition_id' => $expeditionId,
                'client_id' => $clientId,
                'amount' => $totalAmount,
                'status' => 'success',
                'last4' => substr(str_replace(' ', '', $cardValid['card_number']), -4),
                'method' => $cardValid['card_type'] ?? 'Carte'
            ]);

            // Débit du compte client
            $newBalance = $currentBalance - $totalAmount;
            $transactionModel->addTransaction(
                $clientId,
                "Paiement pour l’expédition #$expeditionId",
                0.00,
                $totalAmount,
                $newBalance
            );

            $db->commit();
            unset($_SESSION['expedition_data']);

            header("Location: " . BASE_URL . "/verification/success?id=$paymentId");
            exit;

        } catch (\Exception $e) {
            $db->rollBack();
            $_SESSION['payment_error'] = "Échec du paiement : " . $e->getMessage();
            header("Location: " . BASE_URL . "/payment");
            exit;
        }
    }
}

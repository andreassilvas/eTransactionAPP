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
use App\Models\Command;

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
    //Helper pour envoyer une réponse JSON
    private function json($data, $statusCode)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

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

        // Étape 2 : Vérifie que le user est connecté
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['payment_error'] = "Échec du paiement : Utilisateur non connecté.";
            header("Location: " . BASE_URL . '/payment');
            exit;
        }

        $clientId = $_SESSION['user_id'];
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

        $eco = 0.45;
        $taxes = $totalAmount * 0.15;
        $totalAmount = $totalAmount + $eco + $taxes;

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
            $currentBalance =
                $transactionModel->getCompanyBalance(
                    $client['company_id']
                );

            if ($currentBalance < $totalAmount) {
                throw new \Exception("Fonds insuffisants.");
            }


            // Création de l'expédition
            $expeditionModel = new Expedition();
            $expeditionId = $expeditionModel->create([
                'client_id' => $clientId,
                'company_id' => $client['company_id'],
                'ship_name' => $expeditionData['name'],
                'ship_lastname' => $expeditionData['lastname'],
                'ship_email' => $expeditionData['email'],
                'ship_address' => $expeditionData['address'],
                'ship_city' => $expeditionData['city'],
                'ship_province' => $expeditionData['province'],
                'ship_postcode' => $expeditionData['postcode'],
                'ship_phone' => $expeditionData['phone'],
                'status' => 'success',
                'date' => gmdate('Y-m-d H:i:s')
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
                'company_id' => $client['company_id'],
                'client_id' => $clientId,
                'amount' => $totalAmount,
                'status' => 'success',
                'last4' => substr(str_replace(' ', '', $cardValid['card_number']), -4),
                'method' => $cardValid['card_type'] ?? 'Carte'
            ]);

            // Débit du compte client
            $newBalance = $currentBalance - $totalAmount;
            $transactionModel->create([
                'company_id' => $client['company_id'],
                'user_id' => $clientId,
                'transaction_type' => 'paiement',
                'description' => "expédition #$expeditionId",
                'credit' => 0.00,
                'debit' => $totalAmount,
                'balance' => $newBalance
            ]);

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


    public function paymentAPI()
    {
        require_once __DIR__ . '/../middleware/apiAuth.php';
        apiAuth();



        $data = json_decode(file_get_contents("php://input"), true);

        //API - Payment Validation - check card in DB
        $clientId = $_SESSION['user_id'];
        $cardName = $data['card_name'] ?? '';
        $cardNumber = $data['card_number'] ?? '';
        $codePostal = $data['postcode'] ?? '';
        $expiryDate = $data['expiry_date'] ?? '';
        $cvv = $data['cvv'] ?? '';
        $products = $data['products'] ?? [];

        if (empty($products)) {
            $this->json([
                'status' => 'error',
                'message' => 'Products required'
            ], 400);
        }

        // API - Check stock + calculate total
        $productModel = new Products();

        $subtotal = 0;

        foreach ($products as $item) {
            $prod = $productModel->find($item['id']);

            if (!$prod) {
                $this->json([
                    'status' => 'error',
                    'message' => "Produit introuvable"
                ], 404);
            }

            if ($prod['stock'] < $item['quantity']) {
                $this->json([
                    'status' => 'error',
                    'message' => "Stock insuffisant pour {$prod['name']}"
                ], 400);
            }

            $subtotal += $prod['price'] * $item['quantity'];
        }

        // Calculate final total
        $eco = 0.45;
        $taxes = $subtotal * 0.15;

        $totalAmount = $subtotal + $eco + $taxes;

        // Format validation
        if ($cardNumber === "" && $cardName === "" && $codePostal === "" && $expiryDate === "" && $cvv === "") {
            $this->json(['status' => 'error', 'message' => 'Veuillez saisir vos informations de paiement avant de continuer.'], 400);
        }

        if (!preg_match('/^[A-Za-zÀ-ÿ\s]{2,50}$/u', $cardName)) {
            $this->json(['status' => 'error', 'message' => 'Nom sur la carte requis ou format invalide.'], 400);
        }

        if (!preg_match('/^\d{4}\s\d{4}\s\d{4}\s\d{4}$/', $cardNumber) || $cardNumber === "") {
            $this->json(['status' => 'error', 'message' => 'Numéro de carte : champ requis ou format invalide.'], 400);
        }

        if (!preg_match('/^[A-Za-z]\d[A-Za-z]\s\d[A-Za-z]\d$/', $codePostal)) {
            $this->json(['status' => 'error', 'message' => 'Code postal : champ requis ou format invalide.'], 400);
        }

        if (!preg_match('/^(0[1-9]|1[0-2])\/\d{2}$/', $expiryDate)) {
            $this->json(['status' => 'error', 'message' => "Date d'expiration : champ requis ou format invalide."], 400);
        }

        if (!preg_match('/^\d{3,4}$/', $cvv)) {
            $this->json(['status' => 'error', 'message' => 'CVV : champ requis ou format invalide.'], 400);
        }

        // API - Check card in DB
        $paymentValidationModel = new PaymentValidation();
        $cardValid = $paymentValidationModel->findValidCard(
            $clientId,
            $cardName,
            $cardNumber,
            $codePostal,
            $expiryDate,
            $cvv
        );

        // API - Final card validation
        if (empty($cardValid)) {
            $this->json([
                'status' => 'error',
                'message' => 'Les informations de la carte sont invalides.'
            ], 400);
        }

        //API - Start transaction --------------------------------
        $db = Database::getConnection();

        try {
            $db->beginTransaction();

            //Get client id
            $clientId = $_REQUEST['user']['id'];

            // Check client exists
            $clientModel = new Client();
            $client = $clientModel->findById($clientId);

            if (!$client) {
                throw new \Exception("Client introuvable.");
            }

            // API - Check balance
            $transactionModel = new BankTransaction($db);
            $currentBalance =
                $transactionModel->getCompanyBalance(
                    $client['company_id']
                );

            if ($currentBalance < $totalAmount) {
                throw new \Exception("Fonds insuffisants.");
            }


            // API - Get expedition data from request
            $expeditionModel = new Expedition();

            $expeditionData = $data['expedition'] ?? [];

            if (empty($expeditionData)) {
                $this->json([
                    'status' => 'error',
                    'message' => 'Expedition data required'
                ], 400);
            }

            // API - Création de l'expédition
            $expeditionModel = new Expedition();
            $expeditionId = $expeditionModel->create([
                'company_id' => $client['company_id'],
                'client_id' => $clientId,
                'ship_name' => $expeditionData['name'] ?? '',
                'ship_lastname' => $expeditionData['lastname'] ?? '',
                'ship_email' => $expeditionData['email'] ?? '',
                'ship_address' => $expeditionData['address'] ?? '',
                'ship_city' => $expeditionData['city'] ?? '',
                'ship_province' => $expeditionData['province'] ?? '',
                'ship_postcode' => $expeditionData['postcode'] ?? '',
                'ship_phone' => $expeditionData['phone'] ?? '',
                'status' => 'pending',
                'date' => gmdate('Y-m-d H:i:s')
            ]);

            // API - Création des items et mise à jour du stock
            $expeditionItemModel = new ExpeditionItem();
            foreach ($products as $item) {
                $prod = $productModel->find($item['id']);
                if (!$prod) {
                    throw new \Exception("Produit introuvable.");
                }

                $expeditionItemModel->create([
                    'expedition_id' => $expeditionId,
                    'product_id' => $prod['id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $prod['price']
                ]);

                $newStock = max(0, $prod['stock'] - $item['quantity']);
                $productModel->update($prod['id'], ['stock' => $newStock]);
            }

            //API - Create payment record
            $paymentModel = new Payment();
            $paymentId = $paymentModel->create([
                'company_id' => $client['company_id'],
                'client_id' => $clientId,
                'expedition_id' => $expeditionId,
                'amount' => $totalAmount,
                'status' => 'success',
                'last4' => substr(str_replace(' ', '', $data['card_number']), -4),
                'method' => $data['method'] ?? 'MasterCard'
            ]);

            //API - Bank transaction (balance update)
            $newBalance = $currentBalance - $totalAmount;
            $transactionModel->create([
                'company_id' => $client['company_id'],
                'user_id' => $clientId,
                'transaction_type' => 'paiement',
                'description' => "expédition #$expeditionId",
                'credit' => 0.00,
                'debit' => $totalAmount,
                'balance' => $newBalance
            ]);

            // API - success response
            $db->commit();

            $this->json([
                'status' => 'success',
                'message' => 'Paiement effectué avec succès',
                'payment_id' => $paymentId,
                'expedition_id' => $expeditionId,
                'total' => $totalAmount
            ], 200);

        } catch (\Exception $e) {
            $db->rollBack();

            $this->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function getPaymentDetailsAPI()
    {
        header('Content-Type: application/json');

        $id = $_GET['id'] ?? null;

        if (!$id) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Missing ID'
            ]);
            return;
        }

        require_once __DIR__ . '/../middleware/apiAuth.php';
        apiAuth();

        $paymentModel = new Payment();
        $commandModel = new Command();
        $clientModel = new Client();
        $expeditionModel = new Expedition();

        $payment = $paymentModel->findById($id);

        if (!$payment) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Paiement introuvable'
            ]);
            return;
        }

        $client = $clientModel->findById($payment['client_id']);
        $expedition = $expeditionModel->findWithClientById($payment['expedition_id']);
        $commands = $commandModel->getByPaymentId($id);

        echo json_encode([
            'status' => 'success',
            'payment' => $payment,
            'client' => $client,
            'expedition' => $expedition,
            'commands' => $commands
        ]);
    }
    public function addCardAPI()
    {
        require_once __DIR__ . '/../middleware/apiAuth.php';

        apiAuth();

        header('Content-Type: application/json');

        $data = json_decode(
            file_get_contents('php://input'),
            true
        );

        $clientId =
            (int) ($data['client_id'] ?? 0);

        if ($clientId <= 0) {

            return $this->json([
                'status' => 'error',
                'message' => 'Client invalide'
            ], 400);
        }

        $paymentValidation = new PaymentValidation();
        $clientModel = new Client();

        try {
            $paymentValidation->create([
                'client_id' => $clientId,
                'card_name' => trim($data['card_name']),
                'expiry_date' => trim($data['expiry_date']),
                'card_number' => trim($data['card_number']),
                'code_postal' => trim($data['code_postal']),
                'cvv' => trim($data['cvv']),
                'card_type' => trim(
                    $data['card_type'] ?? 'Visa'
                )
            ]);

            $clientModel->updateById(
                $clientId,
                [
                    'has_card' => 1
                ]
            );

            return $this->json([
                'status' => 'success',
                'clientId' => $clientId,
                'cardName' => $data['card_name'],
                'cardNumber' => $data['card_number'],
                'expiryDate' => $data['expiry_date'],
                'codePostal' => $data['code_postal'],
                'cvv' => $data['cvv'],
                'cardType' => $data['card_type'],
                'message' => 'Carte ajoutée'
            ], 201);

        } catch (\Exception $e) {

            return $this->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}

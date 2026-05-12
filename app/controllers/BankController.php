<?php
namespace App\Controllers;

use App\Models\BankTransaction;
use App\Models\Database;
use App\Models\Client;

/**
 * Class BankController
 *
 * Contrôleur responsable de l'affichage des transactions bancaires du client.
 * Il gère la récupération des données depuis la base et leur transmission à la vue.
 *
 * @package App\Controllers
 */

class BankController
{
    /**
     * Affiche la liste des transactions bancaires du client connecté.
     *
     * Vérifie la session utilisateur, récupère les transactions associées au client
     * et les envoie à la vue correspondante. En cas d'erreur de base de données,
     * une exception PDO est capturée et un message d'erreur est affiché.
     *
     * @return void
     */
    public function index()
    {
        // Vérifie si le client est connecté
        if (!isset($_SESSION['user_id'])) {
            header("Location: " . BASE_URL);
            exit;
        }
        // Récupère l'identifiant du client depuis la session
        $clientId = $_SESSION['user_id'];

        $userModel = new Client();
        $user = $userModel->findById($clientId);
        $companyId = $user['company_id'];

        try {
            // Établit la connexion à la base de données
            $db = Database::getConnection();

            // Crée une instance du modèle BankTransaction
            $transactionModel = new BankTransaction($db);

            // Récupère toutes les transactions liées au client connecté
            $transactions = $transactionModel->getByCompanyId($companyId);
            $companyBalance = $transactionModel->getCompanyBalance($companyId);

            // (Optionnel) — pour déboguer le contenu :
            // var_dump($transactions); exit;

        } catch (\PDOException $e) {
            // Intercepte les erreurs de base de données et affiche un message clair
            die("DB Error: " . $e->getMessage());
        }

        // Charge la vue du relevé bancaire et lui transmet la liste des transactions du client
        require __DIR__ . '/../Views/bank/index.php';
    }
}

<?php
namespace App\Controllers;

// use App\Models\BankTransaction;
// use App\Models\Database;

/**
 * Class BankController
 *
 * Contrôleur responsable de l'affichage des transactions bancaires du client.
 * Il gère la récupération des données depuis la base et leur transmission à la vue.
 *
 * @package App\Controllers
 */

class TableauDeBordController
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
        if (!isset($_SESSION['client_id'])) {
            header("Location: " . BASE_URL);
            exit;
        }
        // Récupère l'identifiant du client depuis la session
        $clientId = $_SESSION['client_id'];

        // Charge la vue du relevé bancaire et lui transmet la liste des transactions du client
        require __DIR__ . '/../Views/tableau/index.php';
    }
}

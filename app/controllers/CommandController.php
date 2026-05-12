<?php
namespace App\Controllers;

use App\Models\Command;
use App\Models\Client;

/**
 * Class CommandController
 *
 * Contrôleur responsable de la gestion et de l'affichage des commandes des clients.
 * Il vérifie la session, récupère les commandes associées au client connecté
 * et les transmet à la vue correspondante.
 *
 * @package App\Controllers
 */

class CommandController
{
    /**
     * Affiche la liste des commandes du client connecté.
     *
     * Vérifie la présence d'une session active, récupère les commandes du client
     * à partir du modèle `Command` et charge la vue correspondante.
     *
     * @return void
     */
    public function index()
    {
        // Vérifie si le client est connecté
        if (!isset($_SESSION['user_id'])) {
            header("Location: " . BASE_URL . '/login');
            exit;
        }

        // Récupère l'identifiant du client depuis la session
        $userId = $_SESSION['user_id'];
        $clientModel = new Client();
        $user = $clientModel->findById($userId);
        $companyId = $user['company_id'];

        // Instancie le modèle Command pour interagir avec la base de données
        $commandModel = new Command();

        // Récupère toutes les commandes liées au client connecté
        $commands =
            $commandModel->getByCompanyId(
                $companyId
            );

        // Charge la vue des commandes et lui transmet la liste des commandes du client
        include __DIR__ . '/../Views/commands/index.php';

        //Debugging line
        // echo "<pre>";
        // print_r($commands);
        // echo "</pre>";
        // error_log(print_r($commands, true));
    }
}

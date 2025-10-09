<?php
namespace App\Controllers;

use Core\Controller;
use App\Models\Client;

/**
 * Class AuthController
 *
 * Contrôleur responsable de la gestion de l'authentification des clients.
 * Il gère la connexion, la vérification des identifiants et la création de session.
 *
 * @package App\Controllers
 */

class AuthController extends Controller
{
    public function login()
    {
        /**
         * Gère la connexion d'un client.
         *
         * Vérifie si les identifiants soumis sont valides, crée la session correspondante
         * et redirige vers la page d'accueil. En cas d'erreur, un message flash est affiché.
         *
         * @return void
         */
        if ($_SERVER["REQUEST_METHOD"] === "POST") {

            // Récupère et nettoie l'adresse email soumise
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

            // Récupère le mot de passe soumis (non filtré pour préserver les caractères spéciaux)
            $password = $_POST['password'] ?? '';

            // Instancie le modèle Client pour interagir avec la base de données
            $userModel = new Client();

            // Recherche un client correspondant à l'adresse email saisie
            $client = $userModel->findByEmail($email);

            // Vérifie si le client existe et si le mot de passe correspond
            if ($client && password_verify($password, $client['password'])) {

                // Enregistre les informations du client dans la session
                $_SESSION['client_id'] = $client['id'];
                $_SESSION['client_name'] = $client['name'];

                // Redirige vers la page d'accueil (liste des produits)
                header("Location: " . BASE_URL);
                exit;
            } else {
                // En cas d'erreur, enregistre un message flash et redirige vers la page de connexion
                $_SESSION['flash']['error'] = 'Email ou mot de passe incorrect';
                header("Location: " . BASE_URL . "/login");
                exit;

            }
        }
    }
}

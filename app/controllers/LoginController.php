<?php
namespace App\Controllers;

require_once __DIR__ . '/../Models/Client.php';
use App\Models\Client;

/**
 * Class LoginController
 *
 * Contrôleur responsable de l'authentification via requêtes AJAX.
 * Vérifie les identifiants d'un client et renvoie une réponse JSON
 * avec le statut de la connexion et la redirection éventuelle.
 *
 * @package App\Controllers
 */

class LoginController
{
    /**
     * Traite la requête de connexion du client.
     *
     * - Vérifie que la requête est de type POST.
     * - Démarre la session si nécessaire.
     * - Vérifie la présence de l'email et du mot de passe.
     * - Authentifie le client et crée les variables de session.
     * - Renvoie une réponse JSON avec le statut de la connexion.
     *
     * @return void
     */
    public function login()
    {
        // Vérifie que la requête est bien une POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
            exit;
        }

        // Démarre la session si elle n'est pas déjà démarrée
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Récupère les données du formulaire
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        // Vérifie que les champs ne sont pas vides
        if (empty($email) || empty($password)) {
            echo json_encode(['status' => 'error', 'message' => 'Veuillez entrer votre email et mot de passe.']);
            exit;
        }

        // Instancie le modèle Client et cherche le client par email
        $clientModel = new Client();
        $client = $clientModel->findByEmail($email);

        // Vérifie que le client existe
        if (!$client) {
            echo json_encode(['status' => 'error', 'message' => 'Email ou mot de passe non trouvé.']);
            exit;
        }

        // Vérifie que le mot de passe correspond
        if ($password !== $client['password']) {
            echo json_encode(['status' => 'error', 'message' => 'Email ou mot de passe non trouvé.']);
            exit;
        }

        /**
         * Authentification réussie
         * - Stocke les informations du client dans la session
         * - Renvoie une réponse JSON avec redirection
         */
        $_SESSION['client_id'] = $client['id'];
        $_SESSION['client_name'] = $client['name'];

        echo json_encode(['status' => 'success', 'redirect' => BASE_URL . '/connexion']);
        exit;
    }

    /**
     * Traite la requête de connexion via API.
     *
     * - Vérifie que la requête est de type POST.
     * - Récupère les données JSON envoyées.
     * - Authentifie le client et renvoie une réponse JSON avec le statut de la connexion.
     *
     * @return void
     */

    public function loginAPI()
    {
        header('Content-Type: application/json');

        // Read JSON input (IMPORTANT for API)
        $data = json_decode(file_get_contents("php://input"), true);

        $email = trim($data['email'] ?? '');
        $password = $data['password'] ?? '';

        if (empty($email) || empty($password)) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Champs requis']);
            return;
        }

        $clientModel = new Client();
        $client = $clientModel->findByEmail($email);

        if (!$client || $password !== $client['password']) {
            http_response_code(401);
            echo json_encode(['status' => 'error', 'message' => 'Identifiants invalides']);
            return;
        }

        //Unauthorized"
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION['client_id'] = $client['id'];

        echo json_encode([
            'status' => 'success',
            'user' => [
                'id' => $client['id'],
                'name' => $client['name']
            ]
        ]);
    }
}

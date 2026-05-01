<?php
namespace App\Controllers;

require_once __DIR__ . '/../Models/Client.php';
use App\Models\Client;
use App\Models\UserToken;

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
        $source = $_POST['source'] ?? 'client';

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

        // Block Client trying to login via Admin modal
        if ($source === 'admin' && $client['role'] !== 'admin') {
            echo json_encode([
                'status' => 'error',
                'message' => 'Accès refusé : compte administrateur requis.'
            ]);
            exit;
        }
        // Block Admin trying to login via Client modal
        if ($source === 'client' && $client['role'] !== 'client') {
            echo json_encode([
                'status' => 'error',
                'message' => 'Veuillez utiliser le portail administrateur.'
            ]);
            exit;
        }

        /**
         * Authentification réussie
         * - Stocke les informations du client dans la session
         * - Renvoie une réponse JSON avec redirection
         */
        $_SESSION['user_id'] = $client['id'];
        $_SESSION['user_name'] = $client['name'];
        $_SESSION['role'] = $client['role'];

        if ($client['role'] === 'admin') {
            $redirect = BASE_URL . '/admin';
        } else {
            $redirect = BASE_URL . '/expedition';
        }
        echo json_encode([
            'status' => 'success',
            'redirect' => $redirect
        ]);
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
        $source = $data['source'] ?? 'client';


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

        // Block Client trying to login via Admin modal
        if ($source === 'admin' && $client['role'] !== 'admin') {
            http_response_code(403);
            echo json_encode([
                'status' => 'error',
                'message' => 'Accès refusé : compte administrateur requis.'
            ]);
            return;
        }

        // Block Admin trying to login via Client modal
        if ($source === 'client' && $client['role'] !== 'client') {
            http_response_code(403);
            echo json_encode([
                'status' => 'error',
                'message' => 'Veuillez utiliser le portail administrateur.'
            ]);
            return;
        }
        //Unauthorized"
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION['user_id'] = $client['id'];
        $_SESSION['role'] = $client['role'];

        if ($client['role'] === 'admin') {
            $redirect = '/admin';
        } else {
            $redirect = '/expedition';
        }

        //TOKEN----------
        $token = bin2hex(random_bytes(32));
        $expiresAt = date('Y-m-d H:i:s', strtotime('+1 day'));//+24 hours from now

        $tokenModel = new UserToken();

        $tokenModel->create($client['id'], $token, $expiresAt);

        setcookie(
            "auth_token",
            $token,
            [
                'expires' => time() + 3600,
                'path' => '/',
                'httponly' => true,
                'secure' => false,
                'samesite' => 'Lax'
            ]
        );

        echo json_encode([
            'status' => 'success',
            'redirect' => $redirect,
            'token' => $token
        ]);
        exit;
    }
    public function logoutAPI()
    {
        $token = $_COOKIE['auth_token'] ?? null;

        if ($token) {
            $tokenModel = new UserToken();
            $tokenModel->deleteByToken($token);
        }

        setcookie(
            "auth_token",
            "",
            [
                'expires' => time() - 3600,
                'path' => '/',
                'httponly' => true,
                'secure' => false,
                'samesite' => 'Lax'
            ]
        );

        echo json_encode([
            'status' => 'success'
        ]);
    }
}

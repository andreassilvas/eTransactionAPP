<?php
namespace App\Controllers;

require_once __DIR__ . '/../models/Client.php';
use App\Models\Client;

class LoginController
{
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
            exit;
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            echo json_encode(['status' => 'error', 'message' => 'Veuillez entrer votre email et mot de passe.']);
            exit;
        }

        $clientModel = new Client();
        $client = $clientModel->findByEmail($email);

        if (!$client) {
            echo json_encode(['status' => 'error', 'message' => 'Email non trouvé.']);
            exit;
        }

        if ($password !== $client['password']) {
            echo json_encode(['status' => 'error', 'message' => 'Mot de passe incorrect.']);
            exit;
        }

        // Success
        $_SESSION['client_id'] = $client['id'];
        $_SESSION['client_name'] = $client['name'];
        echo json_encode(['status' => 'success', 'redirect' => BASE_URL . '/connexion']);
        exit;
    }
}

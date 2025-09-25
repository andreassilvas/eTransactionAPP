<?php
namespace App\Controllers;

require_once __DIR__ . '/../models/Client.php';

use App\Models\Client;

class LoginController
{
    public function show()
    {
        // Optional: pass an error message if one exists
        $error = $_SESSION['login_error'] ?? null;
        unset($_SESSION['login_error']);

        require __DIR__ . '/../Views/loginModal.php';
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL);
            exit;
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $_SESSION['login_error'] = "Veuillez entrer votre email et mot de passe.";
            header("Location: " . BASE_URL);
            exit;
        }

        $clientModel = new Client();
        $client = $clientModel->findByEmail($email); // $client is now an array or false

        if (!$client) {
            $_SESSION['login_error'] = "Email non trouvé.";
            header("Location: " . BASE_URL);
            exit;
        }

        if ($password !== $client['password']) { // plain password for now
            $_SESSION['login_error'] = "Mot de passe incorrect.";
            header("Location: " . BASE_URL);
            exit;
        }

        // Success
        $_SESSION['client_id'] = $client['id'];
        $_SESSION['client_name'] = $client['name'];
        header("Location: " . BASE_URL . "/expedition");
        exit;
    }
}

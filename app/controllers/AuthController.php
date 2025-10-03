<?php
namespace App\Controllers;

use Core\Controller;
use App\Models\Client;

class AuthController extends Controller
{
    public function login()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'] ?? '';

            // Load User model
            $userModel = new Client();
            $client = $userModel->findByEmail($email);

            if ($client && password_verify($password, $client['password'])) {

                $_SESSION['client_id'] = $client['id'];
                $_SESSION['client_name'] = $client['name'];

                // Redirect to home page - Produits
                header("Location: " . BASE_URL);
                exit;
            } else {
                $_SESSION['flash']['error'] = 'Email ou mot de passe incorrect';
                header("Location: " . BASE_URL . "/login");
                exit;

            }
        }
    }
}

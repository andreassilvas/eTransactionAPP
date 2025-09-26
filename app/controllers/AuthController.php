<?php
namespace App\Controllers;

use Core\Controller;
use App\Models\Client;

class AuthController extends Controller
{
    public function login()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            // Load User model
            $userModel = new Client();
            $client = $userModel->findByEmail($email);

            if ($client && password_verify($password, $client['password'])) {
                // Start session if not already started
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                $_SESSION['client_id'] = $client['id'];
                $_SESSION['client_name'] = $client['name'];

                // Redirect to home page - Produits
                header("Location: /eTransactionAPP/public/");
                exit;
            } else {
                // Invalid credentials
                echo "<script>alert('Email ou mot de passe incorrect'); window.history.back();</script>";
            }
        }
    }

    // public function logout()
    // {
    //     if (session_status() === PHP_SESSION_NONE) {
    //         session_start();
    //     }
    //     session_destroy();
    //     header("Location: /eTransactionAPP/public/");
    //     exit;
    // }
}

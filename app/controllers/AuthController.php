<?php
class AuthController extends Controller
{
    public function login()
    {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            // Load User model
            $userModel = new User();
            $user = $userModel->findByEmail($email);

            if ($user && password_verify($password, $user['password'])) {
                // Start session if not already started
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['prenom'];

                // Redirect to home page or dashboard
                header("Location: /eTransactionAPP/public/");
                exit;
            } else {
                // Invalid credentials
                echo "<script>alert('Email ou mot de passe incorrect'); window.history.back();</script>";
            }
        }
    }

    public function logout()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_destroy();
        header("Location: /eTransactionAPP/public/");
        exit;
    }
}

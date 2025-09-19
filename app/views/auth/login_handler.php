<?php
session_start();
require_once __DIR__ . '/../../../config/database.php'; // adjust path if needed

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $_SESSION['login_error'] = "Veuillez entrer votre email et mot de passe.";
        header("Location: /eTransactionAPP/public/");
        exit;
    }

    $pdo = Database::getConnection();
    $stmt = $pdo->prepare("SELECT * FROM clients WHERE email = ?");
    $stmt->execute([$email]);
    $client = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$client) {
        $_SESSION['login_error'] = "Email non trouvé.";
        header("Location: /eTransactionAPP/public/");
        exit;
    }

    if ($password !== $client['password']) { // plain password for now
        $_SESSION['login_error'] = "Mot de passe incorrect.";
        header("Location: /eTransactionAPP/public/");
        exit;
    }

    // success
    $_SESSION['client_id'] = $client['id'];
    $_SESSION['client_name'] = $client['name'];
    header("Location: /eTransactionAPP/public/expedition");
    exit;
}

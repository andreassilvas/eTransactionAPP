<?php include __DIR__ . '/../layouts/header.php'; ?>

<?php
$loginError = $_SESSION['login_error'] ?? null;
$loginEmail = $_SESSION['login_email'] ?? '';
unset($_SESSION['login_error'], $_SESSION['login_email']);
?>

<body>
    <?php include __DIR__ . '/../layouts/navbar.php'; ?>
    <?php include __DIR__ . '/../../Views/auth/login.php'; ?>
    <div class="container my-4">
        <?php include __DIR__ . '/home.php'; ?>
    </div>

    <?php require_once __DIR__ . '/../layouts/footer.php'; ?>
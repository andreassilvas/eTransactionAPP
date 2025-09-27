<?php include __DIR__ . '/../layouts/header.php'; ?>

<?php
$loginError = $_SESSION['login_error'] ?? null;
$loginEmail = $_SESSION['login_email'] ?? '';
unset($_SESSION['login_error'], $_SESSION['login_email']);
?>

<body>
    <?php include __DIR__ . '/../layouts/navbar.php'; ?>
    <?php include __DIR__ . '/../../views/auth/login.php'; ?>


    <div class="container my-4">
        <div class="row g-3">

            <div class="col-sm-4">
                <div class="card rounded-4">
                    <div class="card-body mt-3">
                        <img src="assets/images/server4.webp" alt="server image 1"
                            class="d-inline-block align-text-top mx-auto d-block">
                    </div>
                </div>
            </div>

            <div class="col-sm-4">
                <div class="card rounded-4">
                    <div class="card-body mt-3">
                        <img src="assets/images/server5.webp" alt="server image 2"
                            class="d-inline-block align-text-top mx-auto d-block">
                    </div>
                </div>
            </div>

            <div class="col-sm-4">
                <div class="card rounded-4">
                    <div class="card-body mt-3">
                        <img src="assets/images/server6.webp" alt="server image 3"
                            class="d-inline-block align-text-top mx-auto d-block">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include __DIR__ . '/../layouts/footer.php'; ?>
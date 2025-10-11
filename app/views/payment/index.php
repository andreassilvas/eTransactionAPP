<?php require_once __DIR__ . '/../../middleware/auth.php' ?>
<?php include __DIR__ . '/../layouts/header.php'; ?>

<body>
    <?php $activeStep = 2;
    $logoutUrl = BASE_URL . '/connexion';
    include __DIR__ . '/../layouts/expedition_nav.php'; ?>

    <div class="container my-5">
        <div class=" row">
            <div class="col mb-3">
                <div class="mar-left mb-4">
                    <h3 class="custom-color-b">Paiement</h3>
                </div>
                <?php require __DIR__ . '/payment_form.php'; ?>
            </div>
        </div>
    </div>

    <?php
    // Test the session
    // echo '<pre>';
    // print_r($_SESSION);
    // echo '</pre>';
    // ?>

    <?php require_once __DIR__ . '/../layouts/footer.php'; ?>
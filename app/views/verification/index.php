<?php include __DIR__ . '/../layouts/header.php'; ?>

<body>
    <?php $activeStep = 3;
    $logoutUrl = BASE_URL . '/connexion';
    include __DIR__ . '/../layouts/expedition_nav.php'; ?>

    <div class="container my-5">
        <div class=" row">
            <div class="col mb-3">
                <div class="mar-left">
                    <h3 class="custom-color-b">Vérification de la transaction</h3>
                </div>
                <?php require __DIR__ . '/success.php'; ?>
            </div>
        </div>
    </div>
    <?php
    // Test the session
    echo '<pre>';
    print_r($_SESSION);
    echo '</pre>';
    ?>
    <?php require_once __DIR__ . '/../layouts/footer.php'; ?>
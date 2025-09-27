<?php include __DIR__ . '/../layouts/header.php'; ?>

<body>
    <?php $activeStep = 3;
    $logoutUrl = BASE_URL . '/connexion';
    include __DIR__ . '/../layouts/expedition_nav.php'; ?>

    <div class="container my-5">
        <div class=" row">
            <div class="col mb-3">
                <div class="mar-left">
                    <h4 class="custom-color">Vérification de la transaction</h4>
                </div>
                <?php require __DIR__ . '/success.php'; ?>
            </div>
        </div>
    </div>
    <?php require_once __DIR__ . '/../layouts/footer.php'; ?>
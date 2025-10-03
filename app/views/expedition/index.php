<?php require_once __DIR__ . '/../../middleware/auth.php' ?>
<?php include __DIR__ . '/../layouts/header.php'; ?>

<body>
    <?php $activeStep = 1;
    $logoutUrl = BASE_URL . '/connexion';
    include __DIR__ . '/../layouts/expedition_nav.php'; ?>

    <div class="container my-5">
        <div class="row">
            <div class="col mb-3">
                <div class="mar-left">
                    <h3 class="custom-color-b">Informations d'expédition</h3>
                </div>
                <?php require __DIR__ . '/expedition_form.php'; ?>
            </div>
        </div>
    </div>

    <?php require_once __DIR__ . '/../layouts/footer.php'; ?>
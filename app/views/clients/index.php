<?php include_once __DIR__ . '/../../middleware/auth.php'; ?>
<?php include __DIR__ . '/../layouts/header.php'; ?>

<body>
    <?php $activeStep = 5;
    $logoutUrl = BASE_URL . '/connexion';
    include __DIR__ . '/../layouts/nav_inv.php'; ?>

    <div class="container-fluid p-4">
        <div class="row">
            <div class="col mb-3">
                <?php require __DIR__ . '/clients_form.php'; ?>
                <?php require_once __DIR__ . '/../layouts/modals/success.php'; ?>
            </div>
        </div>
    </div>
    <?php require_once __DIR__ . '/../layouts/footer.php'; ?>
<?php include_once __DIR__ . '/../../middleware/auth.php'; ?>
<?php include __DIR__ . '/../layouts/header.php'; ?>

<body>
    <?php $activeStep = 2;
    $logoutUrl = BASE_URL . '/connexion';
    include __DIR__ . '/../layouts/nav_inv.php'; ?>

    <div class="container-fluid p-4">
        <?php require __DIR__ . '/product_management.php'; ?>
    </div>

    <?php require_once __DIR__ . '/../layouts/footer.php'; ?>
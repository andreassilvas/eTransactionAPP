<?php include_once __DIR__ . '/../../middleware/auth.php'; ?>
<?php include __DIR__ . '/../layouts/header.php'; ?>

<body>
    <?php $logoutUrl = BASE_URL . '/connexion';
    include __DIR__ . '/../layouts/nav_top_dash.php'; ?>

    <div class="position-fixed top-0 start-0 h-100 border-end p-3 my-2" style="width: 260px;">
        <?php include __DIR__ . '/../layouts/nav_side_dash.php'; ?>
    </div>

    <div class="px-4" style="margin-left: 260px; overflow-y: auto;">
        <?php require __DIR__ . '/product_management.php'; ?>
    </div>

    <?php require_once __DIR__ . '/../layouts/footer.php'; ?>
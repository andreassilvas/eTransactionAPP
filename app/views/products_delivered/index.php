<?php include __DIR__ . '/../layouts/header.php'; ?>

<body>
    <?php $logoutUrl = BASE_URL . '/';
    include __DIR__ . '/../layouts/nav_top_dash.php'; ?>

    <div class="position-fixed top-0 start-0 h-100 border-end p-2 my-2" style="width: 275px;">
        <?php include __DIR__ . '/../layouts/nav_side_dash.php'; ?>
    </div>

    <div class="px-4" style="margin-left: 275px; overflow-y: auto;">
        <?php require __DIR__ . '/delivered_product_page.php'; ?>
    </div>

    <?php require_once __DIR__ . '/../layouts/footer.php'; ?>
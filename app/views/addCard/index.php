<?php include __DIR__ . '/../layouts/header.php'; ?>

<body>
    <?php $logoutUrl = BASE_URL . '/';
    include __DIR__ . '/../layouts/nav_top_client.php'; ?>

    <div class="position-fixed top-0 start-0 h-100 border-end p-2 my-2" style="width: 275px;">
        <?php include __DIR__ . '/../layouts/nav_side_clients.php'; ?>
    </div>

    <div class="px-4 mt-3" style="margin-left: 275px; overflow-y: auto;">
        <?php require __DIR__ . '/add_card.php'; ?>
    </div>

    <?php require_once __DIR__ . '/../layouts/footer.php'; ?>
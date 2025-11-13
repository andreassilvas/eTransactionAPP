<?php include_once __DIR__ . '/../../middleware/auth.php'; ?>
<?php include __DIR__ . '/../layouts/header.php'; ?>

<body>
    <?php $activeStep = 2;
    $logoutUrl = BASE_URL . '/connexion';
    include __DIR__ . '/../layouts/nav_releve.php'; ?>

    <div class="container-fluid py-5 px-5">
        <div class=" row">
            <div class="col mb-3">
                <?php require __DIR__ . '/commands_form.php'; ?>
            </div>
        </div>
    </div>

    <!-- <?php
    // Test the session
    echo '<pre>';
    print_r($_SESSION);
    echo '</pre>';
    ?> -->

    <?php require_once __DIR__ . '/../layouts/footer.php'; ?>
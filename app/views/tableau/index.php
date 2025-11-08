<?php include_once __DIR__ . '/../../middleware/auth.php'; ?>
<?php include __DIR__ . '/../layouts/header.php'; ?>

<body>
    <?php $activeStep = 1;
    $logoutUrl = BASE_URL . '/connexion';
    include __DIR__ . '/../layouts/nav_inv.php'; ?>

    <div class="container my-5">
        <div class=" row">
            <div class="col mb-3">
                <div class="mar-left mb-4">
                    <h3 class="custom-color-i">Tableau de Bord</h3>
                </div>
                <div class="card rounded-4">
                    <div class="card-body">
                        <?php require __DIR__ . '/tableau_form.php'; ?>
                        <?php require __DIR__ . '/tableau_notifications.php'; ?>
                    </div>
                </div>
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
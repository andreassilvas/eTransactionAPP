<?php include __DIR__ . '/../layouts/header.php'; ?>

<body>
    <?php $activeStep = 3;
    include __DIR__ . '/../layouts/scrollspy_nav.php'; ?>

    <div class="container my-5">
        <div class=" row">
            <div class="col mb-3">
                <div class="mar-left">
                    <h5 class="">Verification</h5>
                </div>
                <?php require __DIR__ . '/success.php'; ?>
            </div>
        </div>
    </div>
    <?php require_once __DIR__ . '/../layouts/footer.php'; ?>
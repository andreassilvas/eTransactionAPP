<?php include __DIR__ . '/../layouts/header.php'; ?>

<body>
    <?php include __DIR__ . '/../layouts/scrollspy_nav.php'; ?>

    <div class="container my-5">
        <div class=" row">
            <div class="col mb-3">
                <div class="mar-left">
                    <h5 class="">Paiement</h5>
                </div>
                <?php require __DIR__ . '/payment_form.php'; ?>
            </div>
        </div>
    </div>

    <?php require_once __DIR__ . '/../layouts/footer.php'; ?>
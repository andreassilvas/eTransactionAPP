<?php include __DIR__ . '/../layouts/header.php'; ?>

<body>
    <?php $activeStep = 1;
    include __DIR__ . '/../layouts/releve_nav.php'; ?>

    <div class="container my-5">
        <div class=" row">
            <div class="col mb-3">
                <div class="mar-left mb-4">
                    <h4 class="custom-color">Relevé bancaire</h4>
                </div>
                <?php require __DIR__ . '/releve_form.php';




                ?>

            </div>
        </div>
    </div>

    <?php
    // Test the session
    echo '<pre>';
    print_r($_SESSION);
    echo '</pre>';
    ?>

    <?php require_once __DIR__ . '/../layouts/footer.php'; ?>
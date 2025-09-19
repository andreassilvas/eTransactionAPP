<?php require_once __DIR__ . '/../../middleware/auth.php'; ?>
<?php include __DIR__ . '/../layouts/header.php'; ?>

<body>
    <?php include __DIR__ . '/../layouts/scrollspy_nav.php'; ?>

    <div class="container my-5">
        <div class="row">
            <div class="col mb-3">
                <div class="mar-left">
                    <h5 class="">Expédition</h5>
                    <p class="mb-1 mt-3">*Indiquer les renseignements obligatoires</p>
                </div>
                <?php require __DIR__ . '/expedition_form.php'; ?>
            </div>
        </div>
    </div>

    <!-- <h1>Bienvenue sur la page d'expédition, <?= htmlspecialchars($_SESSION['client_name']); ?> !</h1> -->


    <?php require_once __DIR__ . '/../layouts/footer.php'; ?>
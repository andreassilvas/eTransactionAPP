<?php include __DIR__ . '/../layouts/header.php'; ?>

<body class="m-0 overflow-hidden" style="padding-top:0px;">

    <div class="vh-100 position-relative">

        <a href="<?= BASE_URL ?>/" class="btn btn-dark position-absolute top-1 z-3" style="left: 180px; top: 9px;">
            <i class="fa-solid fa-arrow-left"></i> Retour
        </a>

        <object data="<?= BASE_URL ?>/public/view-pdf.php" type="application/pdf" class="w-100 h-100 border-0">

            <div class="text-center mt-5">
                <p>Impossible d'afficher le document PDF.</p>

                <a href="<?= BASE_URL ?>/public/view-pdf.php" target="_blank" class="btn btn-primary">
                    Télécharger le document
                </a>
            </div>

        </object>

    </div>

    <?php require_once __DIR__ . '/../layouts/footer.php'; ?>
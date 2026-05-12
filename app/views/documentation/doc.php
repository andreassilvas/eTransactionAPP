<a href="<?= BASE_URL ?>/" class="btn btn-dark position-absolute top-0 start-0 m-3 z-3">
    <i class="fa-solid fa-arrow-left"></i> Retour
</a>

<object data="<?= BASE_URL ?>/public/view-pdf.php" type="application/pdf" width="100%" height="100%">
    <div class="text-center mt-5">
        <p>Impossible d'afficher le document PDF.</p>

        <a href="<?= BASE_URL ?>/public/view-pdf.php" target="_blank" class="btn btn-primary">
            Télécharger le document
        </a>
    </div>
</object>
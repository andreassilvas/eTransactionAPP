<div class="card">
    <div class="card-header custom-color-white fw-semibold custom-color-inventaire">
        <i class="fa-solid fa-boxes-stacked me-2"></i>


        Inventaire
    </div>
    <div class="card-body">
        <h5 class="card-title mb-4 mt-3 custom-color-d">Accéder à mon inventaire</h5>
        <p class="card-text mb-4">
            Consultez vos articles en stock et gérez vos produits facilement.
            Suivez vos niveaux de stock et optimisez vos ventes.
        </p>
        <div class="d-flex justify-content-center">
            <?php
            $btnText = "Accéder à l'inventaire";
            $btnBg = '#9A5B00';
            $btnBorder = '#9A5B00';
            $btnTextColor = '#fff';
            $btnHoverBg = '#2B3035';
            $btnHoverBorder = '#2B3035';
            $href = BASE_URL . '/tableau-de-bord';
            include __DIR__ . '/../components/base_button.php';
            ?>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-header custom-color-white fw-semibold custom-color-inventaire">Inventaire</div>
    <div class="card-body">
        <h5 class="card-title mb-4 mt-3 custom-color-d">Accéder à mon inventaire</h5>
        <p class="card-text mb-4">
            Consultez vos articles en stock et gérez vos produits facilement.
            Suivez vos niveaux de stock et optimisez vos ventes.
        </p>
        <div class="d-flex justify-content-center">
            <?php
            $btnText = "Accéder à l'inventaire";
            $btnBg = '#064747';
            $btnBorder = '#064747';
            $btnTextColor = '#fff';
            $btnHoverBg = '#005F66';
            $btnHoverBorder = '#005F66';
            $href = BASE_URL . '/tableau-de-bord';
            include __DIR__ . '/../components/base_button.php';
            ?>
        </div>
    </div>
</div>
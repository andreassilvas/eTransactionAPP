<div class="d-grid gap-2 d-flex justify-content-center mt-5">
    <div class="">
        <?php
        $btnText = "Produits en Vente : 200";
        $btnBg = '#000047';
        $btnBorder = '#000047';
        $btnTextColor = '#fff';
        $btnHoverBg = '#546373';
        $btnHoverBorder = '#546373';
        $href = BASE_URL . '/produits-en-stock';

        include __DIR__ . '/../components/base_button.php';
        ?>
    </div>
    <div class="">
        <?php
        $btnText = "Produits en Rupture : 5";
        $href = BASE_URL . '/produits-en-rupture';

        include __DIR__ . '/../components/base_button.php';
        ?>
    </div>
    <div class="">
        <?php
        $btnText = "Utilisateurs Actifs : 50";
        $href = BASE_URL . '/utilisateurs-actifs';

        include __DIR__ . '/../components/base_button.php';
        ?>
    </div>
</div>
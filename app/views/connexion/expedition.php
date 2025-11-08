<div class="card">
    <div class="card-header custom-color-white fw-semibold custom-color-expedition">Expédition</div>
    <div class="card-body">
        <h5 class="card-title mb-4 mt-3 custom-color-d">Connexion à l’expédition</h5>
        <p class="card-text mb-4">
            Remplissez le formulaire d’expédition et procédez au paiement de vos articles.
            Suivez vos envois et assurez une livraison sans souci.
        </p>

        <div class="d-flex justify-content-center">
            <?php
            $btnText = "Expédier un article";
            $btnBg = '#005F66';
            $btnBorder = '#005F66';
            $btnTextColor = '#fff';
            $btnHoverBg = '#00738A';
            $btnHoverBorder = '#00738A';
            $href = BASE_URL . '/expedition';

            include __DIR__ . '/../components/base_button.php';
            ?>
        </div>
    </div>
</div>
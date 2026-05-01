<div class="card">
    <div class="card-header custom-color-releve custom-color-white fw-semibold">Relevés Client</div>
    <div class="card-body">
        <h5 class="card-title mb-4 mt-3 custom-color-d">Accéder à mes relevés</h5>
        <p class="card-text mb-4">
            Consultez vos relevés bancaires et vos commandes en toute sécurité.
            Gardez une trace de vos transactions.
        </p>
        <div class="d-flex justify-content-center">
            <?php
            $btnText = "Accéder aux relevés";
            $btnBg = '#005F66';
            $btnBorder = '#005F66';
            $btnTextColor = '#fff';
            $btnHoverBg = '#00738A';
            $btnHoverBorder = '#00738A';
            $href = BASE_URL . '/releve';
            include __DIR__ . '/../components/base_button.php';
            ?>
        </div>
    </div>
</div>
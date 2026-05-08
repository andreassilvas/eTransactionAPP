<div class="card">
    <div class="card-header custom-color-releve custom-color-white fw-semibold"> <i
            class="fa-solid fa-building-columns me-2"></i>Accès bancaire</div>
    <div class="card-body">
        <h5 class="card-title mb-4 mt-3 custom-color-d">Accès à mon compte bancaire</h5>
        <p class="card-text mb-4">
            Accédez à vos relevés bancaires et à l'historique de vos transactions en toute sécurité.
        </p>
        <div class="d-flex justify-content-center">
            <?php
            $btnText = "Mon compte";
            $btnBg = '#061E3A';
            $btnBorder = '#061E3A';
            $btnTextColor = '#fff';
            $btnHoverBg = '#0B3D91';
            $btnHoverBorder = '#0B3D91';
            $href = BASE_URL . '/banque';
            include __DIR__ . '/../components/base_button.php';
            ?>
        </div>
    </div>
</div>
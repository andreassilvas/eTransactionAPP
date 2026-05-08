<div class="card">
    <div class="card-header custom-color-releve custom-color-white fw-semibold"><i
            class="fa-solid fa-users me-2"></i>Gestion des clients</div>
    <div class="card-body">
        <h5 class="card-title mb-4 mt-3 custom-color-d">Gérer les accès et les autorisations des clients</h5>
        <p class="card-text mb-4">
            Cette section vous permet de gérer les accès des clients aux ressources ainsi que les actions qu’ils sont
            autorisés à effectuer.
        </p>
        <div class="d-flex justify-content-center">
            <?php
            $btnText = "Mon compte";
            $btnBg = '#0B3D2E';
            $btnBorder = '#0B3D2E';
            $btnTextColor = '#fff';
            $btnHoverBg = '#2B3035';
            $btnHoverBorder = '#2B3035';
            $href = BASE_URL . '/banque';
            include __DIR__ . '/../components/base_button.php';
            ?>
        </div>
    </div>
</div>
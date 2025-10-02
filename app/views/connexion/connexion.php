<?php
$clientName = htmlspecialchars($_SESSION['client_name'] ?? ''); // fallback to empty if not set
?>
<div class="container pt-5 pb-5">
    <h2 class="fw-light custom-color-b mb-4">Heureux de vous revoir, <span
            class="custom-color-b fw-semibold"><?= $clientName ?></span>
    </h2>
</div>
<div class="row justify-content-center mt-3">
    <div class="col-sm-6 mb-3 mb-sm-0">
        <div class="card">
            <div class="card-header custom-color-d fw-semibold">Relevés</div>
            <div class="card-body">
                <h5 class="card-title mb-4 mt-3 custom-color-d">Accéder à mes relevés</h5>
                <p class="card-text mb-4">
                    Consultez vos relevés bancaires et vos commandes en toute sécurité.
                    Gardez une trace de vos transactions et suivez vos achats facilement.
                </p>
                <div class="d-flex justify-content-start">
                    <?php
                    $btnText = "Accéder";
                    $btnBg = '#546373';
                    $btnBorder = '#546373';
                    $btnTextColor = '#fff';
                    $btnHoverBg = '#6D8196';
                    $btnHoverBorder = '#6D8196';
                    $href = BASE_URL . '/releve';
                    include __DIR__ . '/../components/base_button.php';
                    ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6">
        <div class="card">
            <div class="card-header custom-color-d fw-semibold">Expédition</div>
            <div class="card-body">
                <h5 class="card-title mb-4 mt-3 custom-color-d">Connexion à l’expédition</h5>
                <p class="card-text mb-4">
                    Remplissez le formulaire d’expédition et procédez au paiement de vos articles.
                    Suivez vos envois et assurez une livraison sans souci.
                </p>
                <div class="d-flex justify-content-end">
                    <div class="d-flex justify-content-end">
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
        </div>
    </div>
</div>
</div>
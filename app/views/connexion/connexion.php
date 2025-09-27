<div class="container pt-5 pb-5">
    <h2 class="fw-light mb-4">Bon retour, Lulu</h2>
</div>
<div class="row justify-content-center mt-3">
    <div class="col-sm-6 mb-3 mb-sm-0">
        <div class="card">
            <div class="card-header">Relevés</div>
            <div class="card-body">
                <h5 class="card-title mb-4 mt-3">Accéder à mes relevés</h5>
                <p class="card-text mb-4">
                    Consultez vos relevés bancaires et vos commandes en toute sécurité.
                    Gardez une trace de vos transactions et suivez vos achats facilement.
                </p>
                <div class="d-flex justify-content-end">
                    <?php
                    $btnText = "Accéder";
                    $href = BASE_URL . '/releve';
                    include __DIR__ . '/../components/base_button.php';
                    ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6">
        <div class="card">
            <div class="card-header">Expédition</div>
            <div class="card-body">
                <h5 class="card-title mb-4 mt-3">Connexion à l’expédition</h5>
                <p class="card-text mb-4">
                    Remplissez le formulaire d’expédition et procédez au paiement de vos articles.
                    Suivez vos envois et assurez une livraison sans souci.
                </p>
                <div class="d-flex justify-content-end">
                    <?php
                    $btnText = "Expédier un article";
                    $href = BASE_URL . '/expedition';
                    include __DIR__ . '/../components/base_button.php';
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
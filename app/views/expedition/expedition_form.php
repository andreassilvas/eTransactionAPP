<form method="POST" action="/eTransactionAPP/public/expeditions/store">
    <div class="row">
        <div class="col-8 pad-left">
            <div class="card mb-4 rounded-4">
                <div class="card-body mt-3">
                    <h6 class="card-title">Coordonnées</h6>
                    <div class="mb-3 col-sm-7">
                        <?php include __DIR__ . '/expedition-inputs/email.php'; ?>

                    </div>
                </div>

            </div>
            <div class="card rounded-4">
                <div class="card-body mt-3">
                    <h6 class="card-title">Adresse de livraison</h6>
                    <div class="row">
                        <div class="col">
                            <?php include __DIR__ . '/expedition-inputs/prenom.php' ?>
                        </div>
                        <div class="col">
                            <?php include __DIR__ . '/expedition-inputs/nomfamille.php' ?>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col">
                            <?php include __DIR__ . '/expedition-inputs/telephone.php' ?>
                        </div>
                        <div class="col">
                            <?php include __DIR__ . '/expedition-inputs/extention.php' ?>
                        </div>
                    </div>
                    <div class="mt-3">
                        <?php include __DIR__ . '/expedition-inputs/adresse.php' ?>
                    </div>
                    <div class="row g-3 mt-3">
                        <div class="col-sm-7">
                            <?php include __DIR__ . '/expedition-inputs/ville.php' ?>
                        </div>
                        <div class="col-sm">
                            <?php include __DIR__ . '/expedition-inputs/province.php' ?>
                        </div>
                        <div class="col-sm">
                            <?php include __DIR__ . '/expedition-inputs/codepostal.php' ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-4 pad-right">
            <div class="card rounded-4">
                <div class="card-body">
                    <?php include __DIR__ . '/../layouts/resume_commande.php' ?>
                    <div class="d-grid mt-3">
                        <?php
                        $btnText = "Continuer";
                        $btnType = "submit";
                        include __DIR__ . '/../components/base_button.php' ?>
                    </div>
                    <?php include __DIR__ . "/../layouts/base-form-inputs/security_message.php";
                    ?>
                </div>
            </div>
        </div>
    </div>
</form>
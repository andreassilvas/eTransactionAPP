<form method="POST" action="/eTransactionAPP/public/expeditions/store" id="billingForm">
    <div class="row">
        <div class="col-8 pad-left">
            <div class="card rounded-4 mt-3 mb-3">
                <div class="card-body mt-3">
                    <div class="d-flex align-items-center mb-2">
                        <input type="hidden" name="use_billing_address" value="1">
                        <?php
                        $btnText = "Même adresse";
                        $btnType = "submit";
                        include __DIR__ . '/../components/base_button.php';
                        ?>
                        <div class="ms-3">
                            <h6 class="mb-0 custom-color">Utiliser mon adresse de facturation comme adresse de
                                livraison.
                            </h6>
                            <p class="mb-0 custom-color-light">Sinon, saisissez une nouvelle adresse et cliquez sur
                                Continuer.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<form method="POST" action="/eTransactionAPP/public/expeditions/store">
    <div class="row">
        <div class="col-8 pad-left">
            <div class="card rounded-4">
                <div class="card-body mt-3">
                    <h5 class="card-title custom-color">Nouvelle adresse de livraison</h5>
                    <p class="custom-color-light">*Indiquer les renseignements obligatoires</p>
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
                    <div class="row mt-3">
                        <div class="col">
                            <?php include __DIR__ . '/expedition-inputs/email.php'; ?>
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
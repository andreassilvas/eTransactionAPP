<?php require_once __DIR__ . '/../../models/Expedition.php'; ?>

<!-- Payment Form -->
<form method="POST" action="/eTransactionAPP/public/payment/process">
    <div class="row">
        <div class="col-8 pad-left">
            <div class="card rounded-top-4">
                <div class="card-body">
                    <div class="col-sm-7">
                        Carte de credit
                    </div>
                </div>
            </div>

            <div class="card rounded-top-0 mt-2">
                <div class="card-body mt-3">
                    <h6 class="card-title">Renseignements sur la carte</h6>

                    <div class="card rounded-4">
                        <div class="card-body mt-3">
                            <div class="mb-3 col-sm-7">
                                <p class="card-title">Nous acceptons les cartes suivantes :</p>
                                <div class="d-flex gap-3">
                                    <img src="assets/images/visa_card.webp" class="img-fluid" alt="Visa"
                                        style="max-width: 30px;">
                                    <img src="assets/images/master_card.webp" class="img-fluid" alt="MasterCard"
                                        style="max-width: 30px;">
                                    <img src="assets/images/amex_card.webp" class="img-fluid" alt="Amex"
                                        style="max-width: 30px;">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <?php include __DIR__ . '/payment-inputs/nro_carte.php'; ?>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-3">
                                    <?php include __DIR__ . '/payment-inputs/expiration_month.php'; ?>
                                </div>
                                <div class="col-sm-3">
                                    <?php include __DIR__ . '/payment-inputs/expiration_year.php'; ?>
                                </div>
                                <div class="col-sm-3">
                                    <?php include __DIR__ . '/payment-inputs/nro_cvv.php'; ?>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-3">
                                    <?php include __DIR__ . '/../expedition/expedition-inputs/codepostal.php'; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Expedition Info -->
                    <div class="card rounded-4 mt-2">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <?php
                                    if (!empty($_SESSION['expedition_data'])) {
                                        $expedition = $_SESSION['expedition_data'];
                                        echo htmlspecialchars($expedition['name']) . " " . htmlspecialchars($expedition['lastname']) . "<br>";
                                        echo htmlspecialchars($expedition['address']) . "<br>";
                                        echo htmlspecialchars($expedition['city']) . ", " . htmlspecialchars($expedition['province']) . ", " . htmlspecialchars($expedition['postcode']) . "<br>";
                                        echo htmlspecialchars($expedition['phone']);
                                    } else {
                                        echo "Aucune expédition trouvée.";
                                    }
                                    ?>
                                </div>
                                <div class="col-sm-6 text-end pe-5">
                                    <i class="fa-sharp fa-solid fa-circle-check fa-lg" style="color: #008000;"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Hidden expedition_id for PaymentController -->
                    <?php if (!empty($_SESSION['expedition_data'])): ?>
                        <input type="hidden" name="expedition_id"
                            value="<?= htmlspecialchars($_SESSION['expedition_data']['id'] ?? '') ?>">
                    <?php endif; ?>
                    <!-- Payment error -->
                    <?php if (!empty($_SESSION['payment_error'])): ?>
                        <div class="alert alert-danger mt-3">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                            <?= htmlspecialchars($_SESSION['payment_error']) ?>
                        </div>
                        <?php unset($_SESSION['payment_error']); ?>
                    <?php endif; ?>



                </div>
            </div>
        </div>

        <!-- Summary & Submit -->
        <div class="col-4 pad-right">
            <div class="card rounded-4">
                <div class="card-body">
                    <?php include __DIR__ . '/../layouts/resume_commande.php'; ?>

                    <div class="d-grid mt-3">
                        <?php
                        $btnText = "Continuer";
                        $btnType = "submit";
                        include __DIR__ . '/../components/base_button.php';
                        ?>
                    </div>

                    <?php include __DIR__ . '/../layouts/base-form-inputs/security_message.php'; ?>
                </div>
            </div>
        </div>
    </div>
</form>
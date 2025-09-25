<?php
use App\Models\Payment;
use App\Models\Expedition;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Get payment ID from query
$paymentId = $_GET['id'] ?? null;

if (!$paymentId) {
    echo "Aucune transaction trouvée.";
    exit;
}

// Load payment info
$paymentModel = new Payment();
$payment = $paymentModel->findById($paymentId);

if (!$payment) {
    echo "Paiement introuvable.";
    exit;
}

// Load expedition info
$expeditionModel = new Expedition();
// $expedition = $expeditionModel->findById($payment['expedition_id']);
$expedition = $expeditionModel->findWithClientById($payment['expedition_id']);

?>

<div class="col-8 pad-left pt-4">
    <div class="card mb-4 rounded-4">
        <div class="card-body mt-3">
            <div class="d-flex justify-content-end align-items-center pe-2">
                <span class="d-flex align-items-center custom-success fw-bold">
                    Transaction réussie
                    <i class="fa-sharp fa-solid fa-circle-check fa-lg ms-2 custom-success"></i>
                </span>
            </div>

            <h6 class="card-title custom-color mb-2">Informations client</h6>
            <div class="card mb-4 rounded-4">
                <div class="card-body">
                    <ul class="list-group">
                        <li class="list-group item align-items-end">Client ID:
                            <?= htmlspecialchars($payment['id']) ?>
                        </li>
                        <li class="list-group item"><?= htmlspecialchars($expedition['name']) ?>
                            <?= htmlspecialchars($expedition['lastname']) ?>
                        </li>
                        <li class="list-group item"><?= htmlspecialchars($expedition['ship_address']) ?>
                            <?= htmlspecialchars($expedition['ship_city']) ?>
                            <?= htmlspecialchars($expedition['ship_province']) ?>
                            <?= htmlspecialchars($expedition['ship_postcode']) ?>
                        </li>
                        <li class="list-group item"><?= htmlspecialchars($expedition['phone']) ?></li>
                    </ul>
                </div>
            </div>

            <h6 class="card-title custom-color">Informations sur l'expédition</h6>
            <div class="card mb-4 rounded-4">
                <div class="card-body">
                    <?php if ($expedition): ?>
                        <ul class="list-group">
                            <li class="list-group item align-items-end">Tracking Nro :
                                <?= htmlspecialchars($expedition['tracking_number']) ?>
                            </li>
                            <li class="list-group item align-items-end">Expedition ID:
                                <?= htmlspecialchars($payment['expedition_id']) ?>
                            </li>
                            <li class="list-group item">Name : <?= htmlspecialchars($expedition['name']) ?>
                                <?= htmlspecialchars($expedition['lastname']) ?>
                            </li>

                            <li class="list-group item">Adresse : <?= htmlspecialchars($expedition['ship_address']) ?>
                                <?= htmlspecialchars($expedition['ship_city']) ?>
                                <?= htmlspecialchars($expedition['ship_province']) ?>
                                <?= htmlspecialchars($expedition['ship_postcode']) ?>
                            </li>
                            <li class="list-group item"><?= htmlspecialchars($expedition['phone']) ?></li>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
            <h6 class="card-title custom-color">Résumé de la commande</h6>
            <div class="card mb-1 rounded-top-4">
                <div class="card-body">
                    <h6 class="card-title">ThinkSystem ST250 V3</h6>
                    <h6 class="card-title">Fiche technique du système :</h6>
                    <ul class="list-group">
                        <li class="list-group item">Processor : Intel® Xeon® Raptor E-2414 4C 2.6G 55W</li>
                        <li class="list-group item">Capacité totale de la mémoire : 128 GB/TruDDR5 </li>
                        <li class="list-group item">Mémoire incluse : 16 GB 1Rx8</li>
                    </ul>
                </div>
            </div>
            <div class="card mb-0 rounded-bottom-4">
                <div class="card-body">
                    <?php if ($expedition): ?>
                        <div class="container text-end">
                            <div class="row justify-content-md-end">
                                <div class="col-2 custom-color fw-bold">
                                    Total :
                                </div>
                                <div class="col-2">
                                    2599.99$
                                </div>
                            </div>
                            <div class="row justify-content-md-end">
                                <div class="col-4 custom-color fw-bold">
                                    Frais de livraison :
                                </div>
                                <div class="col-2">
                                    Gratuit
                                </div>
                            </div>
                            <div class="row justify-content-md-end">
                                <div class="col-4 custom-color fw-bold">
                                    Écofrais :
                                </div>
                                <div class="col-2">
                                    0.45$
                                </div>
                            </div>
                            <div class="row justify-content-md-end">
                                <div class="col-4 custom-color fw-bold">
                                    Taxes :
                                </div>
                                <div class="col-2">
                                    389.41$$
                                </div>
                            </div>
                            <div class="row justify-content-md-end">
                                <div class="col-4 custom-color fw-bold">
                                    Montant payé :
                                </div>
                                <div class="col-2">
                                    <?= htmlspecialchars($payment['amount']) ?> $
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>


<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
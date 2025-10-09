<?php
use App\Models\Command;
use App\Models\Payment;
use App\Models\Expedition;
use App\Models\Client;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Récupération de l'ID du paiement depuis la query string
$paymentId = $_GET['id'] ?? null;

if (!$paymentId) {
    echo "Aucune transaction trouvée.";
    exit;
}

// Charger les informations du paiement
$paymentModel = new Payment();
$payment = $paymentModel->findById($paymentId);

if (!$payment) {
    echo "Paiement introuvable.";
    exit;
}

// Charger les informations du client connecté
$clientModel = new Client();
$client = $clientModel->findById($_SESSION['client_id']);

// Charger les informations de l'expédition
$expeditionModel = new Expedition();
$expedition = $expeditionModel->findWithClientById($payment['expedition_id']);

// Charger les commandes du client
$commandModel = new Command();
$clientId = $_SESSION['client_id'];
$commands = $commandModel->getByClientId($clientId);
?>

<div class="col-9 pad-left pt-4">
    <div class="card mb-4 rounded-4 px-3">
        <div class="card-body mt-3">

            <!-- Message de succès -->
            <div class="d-flex justify-content-start align-items-center pb-4">
                <div class="d-flex align-items-center custom-success fw-bold">
                    <h5>Transaction réussie</h5>
                    <i class="fa-sharp fa-solid fa-circle-check fa-lg ms-2 custom-success"></i>
                </div>
            </div>

            <!-- Informations client -->
            <h5 class="card-title custom-color-b mb-2">Informations sur le client</h5>
            <div class="card mb-4 rounded-4">
                <div class="card-body">
                    <ul class="list-group custom-color-dark">
                        <li class="list-group-item border-0 py-1"><?= htmlspecialchars($client['name']) ?>
                            <?= htmlspecialchars($client['lastname']) ?>
                        </li>
                        <li class="list-group-item border-0 py-1"><?= htmlspecialchars($client['address']) ?>,
                            <?= htmlspecialchars($client['city']) ?>,
                            <?= htmlspecialchars($client['province']) ?>,
                            <?= htmlspecialchars($client['postcode']) ?>
                        </li>
                        <li class="list-group-item border-0 py-1"><?= htmlspecialchars($client['email']) ?></li>
                        <li class="list-group-item border-0 py-1"><?= htmlspecialchars($client['phone']) ?></li>
                    </ul>
                </div>
            </div>

            <h5 class="card-title custom-color-b">Informations sur l'expédition</h5>
            <div class="card mb-4 rounded-4">
                <div class="card-body">
                    <?php if ($expedition): ?>
                        <ul class="list-group">
                            <li
                                class="list-group-item d-flex justify-content-between align-items-center border-0 fw-semibold">
                                <span>
                                    <span class="custom-color-c">Date de création :</span>
                                    <?= htmlspecialchars($expedition['date']) ?></span>

                                <span>
                                    <i class="fa-solid fa-truck-fast fa-lg custom-color-a" style="margin-right: 10px;"></i>
                                    <span class="custom-color-c">Numéro de suivi :</span>
                                    <span><?= htmlspecialchars($expedition['tracking_number']) ?></span>

                                </span>
                            </li>
                            <li
                                class="list-group-item border-0 d-flex justify-content-end align-items-center py-0 fw-semibold">
                                <span class="px-3">
                                    <span class="custom-color-c">ID Expédition :</span>
                                    <?= htmlspecialchars($payment['expedition_id']) ?></span>
                                <span>
                                    <span class="custom-color-c">Statut :</span>
                                    <?= htmlspecialchars($expedition['status']) ?></span>
                            </li>

                            <li class="list-group-item border-0 py-1">Nom :
                                <?= htmlspecialchars($expedition['ship_name']) ?>
                                <?= htmlspecialchars($expedition['ship_lastname']) ?>
                            </li>

                            <li class="list-group-item border-0 py-1">Adresse :
                                <?= htmlspecialchars($expedition['ship_address']) ?>,
                                <?= htmlspecialchars($expedition['ship_city']) ?>,
                                <?= htmlspecialchars($expedition['ship_province']) ?>,
                                <?= htmlspecialchars($expedition['ship_postcode']) ?>
                            </li>
                            <li class="list-group-item border-0 py-1">Courriel :
                                <?= htmlspecialchars($expedition['ship_email']) ?>
                            </li>
                            <li class="list-group-item border-0 py-1">Téléphone :
                                <?= htmlspecialchars($expedition['ship_phone']) ?>
                            </li>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
            <h5 class="card-title custom-color-b">Résumé de la commande</h5>
            <div class="card mb-1 rounded-top-4">
                <div class="card-body">
                    <!-- <ul class="list-group">
                        <?php if (!empty($commands)): ?>
                            <?php foreach ($commands as $command): ?>
                                <li class="list-group-item border-0 py-1">
                                    <?= htmlspecialchars($command['products']) ?>
                                </li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li class="list-group-item">Aucune commande trouvée.</li>
                        <?php endif; ?>

                    </ul> -->
                    <h6 class="card-title">Cisco UCS C220 M6</h6>
                    <h6 class="card-title">Fiche technique du système :</h6>
                    <ul class="list-group">
                        <li class="list-group-item border-0">Model : C220 M6</li>
                        <li class="list-group-item border-0">CPU : Intel Xeon Silver 4310 12C 2.1GHz</li>
                        <li class="list-group-item border-0">RAM : 64GB DDR4</li>
                        <li class="list-group-item border-0">Storage : 2x1TB SSD</li>
                    </ul>
                </div>
            </div>
            <div class="card mb-0 rounded-bottom-4">
                <div class="card-body">
                    <?php if ($expedition): ?>
                        <div class="container text-end">
                            <div class="row d-flex justify-content-end pb-1">
                                <div class="col-2 custom-color-b fw-bold">
                                    Total :
                                </div>
                                <div class="col-2">
                                    3999.00$
                                </div>
                            </div>
                            <div class="row d-flex justify-content-end pb-1">
                                <div class="col-4 custom-color-b fw-bold">
                                    Frais de livraison :
                                </div>
                                <div class="col-2">
                                    Gratuit
                                </div>
                            </div>
                            <div class="row d-flex justify-content-end pb-1">
                                <div class="col-4 custom-color-b fw-bold">
                                    Écofrais :
                                </div>
                                <div class="col-2">
                                    0.45$
                                </div>
                            </div>
                            <div class="row d-flex justify-content-end pb-1">
                                <div class="col-4 custom-color-b fw-bold">
                                    Taxes :
                                </div>
                                <div class="col-2">
                                    389.41$
                                </div>
                            </div>
                            <div class="row pb-1">
                                <div class="col-7 d-flex justify-content-start custom-color-c">
                                    <p>Mode de paiement : carte **** **** ****
                                        <?= htmlspecialchars($payment['last4']) ?>
                                    </p>
                                </div>
                                <div class=" col-3 custom-color-b fw-bold pb-1">
                                    Montant payé :
                                </div>
                                <div class="col-2">
                                    <?= htmlspecialchars($payment['amount']) ?>$
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
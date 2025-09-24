<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../models/Payment.php';
require_once __DIR__ . '/../../models/Expedition.php';

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
$expedition = $expeditionModel->findById($payment['expedition_id']);

?>

<div class="container my-5">
    <h2>Paiement réussi !</h2>
    <p>Montant payé : <?= htmlspecialchars($payment['amount']) ?> $</p>

    <?php if ($expedition): ?>
        <h4>Expédition :</h4>
        <p>
            Expedition ID: <?= htmlspecialchars($payment['expedition_id']) ?><br>
            Adresse : <?= htmlspecialchars($expedition['ship_address']) ?><br>
            Ville : <?= htmlspecialchars($expedition['ship_city']) ?><br>
            Code postal : <?= htmlspecialchars($expedition['ship_postcode']) ?>
        </p>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
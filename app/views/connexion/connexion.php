<?php
// Récupération du nom du client depuis la session, sécurisation avec htmlspecialchars
$clientName = htmlspecialchars($_SESSION['client_name'] ?? '');
?>
<div class="container pt-5 pb-5">
    <h2 class="fw-light custom-color-c mb-4">Heureux de vous revoir, <span
            class="custom-color-c fw-semibold"><?= $clientName ?></span>
    </h2>
</div>
<div class="row justify-content-center mt-3">
    <div class="col-sm-4">
        <?php include __DIR__ . '/../connexion/inventaire.php'; ?>
    </div>
    <div class="col-sm-4 mb-3 mb-sm-0">
        <?php include __DIR__ . '/../connexion/releves.php'; ?>
    </div>
    <div class="col-sm-4">
        <?php include __DIR__ . '/../connexion/expedition.php'; ?>
    </div>
</div>
</div>
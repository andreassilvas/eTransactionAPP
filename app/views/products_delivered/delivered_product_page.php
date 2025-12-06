<?php
$expStatus = $expStatus ?? [];
$shipped = $shipped ?? [];
$delivered = $delivered ?? [];
$pending = $pending ?? [];
?>

<div class="card px-3 pb-3">
    <div class="card-body">
        <h3 class="card-title custom-color-i pt-4">Produits expédiés</h3>
        <p class="card-text mb-5 custom-color-i">Vue globale du produits expédiés.</p>

        <div class="row g-3">
            <div class="col-md-3">
                <div class="card shadow-sm h-100">
                    <div class="card-header text-center fw-semibold custom-header-dashboard">Statut des expéditions
                    </div>
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <canvas id="chart-expedition"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm h-100">
                    <div class="card-header text-center fw-semibold custom-header-dashboard">Produit(s) livré(s)</div>
                    <div class="card-body">
                        <?php if (empty($delivered)): ?>
                            <div class="text-muted small custom-color-i">Aucun produit livré enregistrée.</div>
                        <?php else: ?>
                            <table class="table mb-0 align-middle">
                                <thead>
                                    <tr>
                                        <th class="custom-color-i">Produit</th>
                                        <th class="text-end custom-color-i">Quantité livré</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($delivered as $item): ?>
                                        <tr>
                                            <td class="custom-success"><?= htmlspecialchars($item['product_name']) ?></td>
                                            <td class="text-end custom-success"><?= (int) $item['total_quantity'] ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm h-100">
                    <div class="card-header text-center fw-semibold custom-header-dashboard">Produit(s) expédié(s)</div>
                    <div class="card-body">
                        <?php if (empty($shipped)): ?>
                            <div class="text-muted small custom-color-i">Aucun produit expédié enregistrée.</div>
                        <?php else: ?>
                            <table class="table mb-0 align-middle">
                                <thead>
                                    <tr>
                                        <th class="custom-color-i">Produit</th>
                                        <th class="text-end custom-color-i">Quantité expédié</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($shipped as $item): ?>
                                        <tr>
                                            <td class="custom-success"><?= htmlspecialchars($item['product_name']) ?></td>
                                            <td class="text-end custom-success"><?= (int) $item['total_quantity'] ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm h-100">
                    <div class="card-header text-center fw-semibold custom-header-dashboard">Produit(s) en attente</div>
                    <div class="card-body">
                        <?php if (empty($pending)): ?>
                            <div class="text-muted small custom-color-i">Aucun produit en attente enregistrée.</div>
                        <?php else: ?>
                            <table class="table mb-0 align-middle">
                                <thead>
                                    <tr>
                                        <th class="custom-color-i">Produit</th>
                                        <th class="text-end custom-color-i">Quantité en attente</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($pending as $item): ?>
                                        <tr>
                                            <td class="custom-success"><?= htmlspecialchars($item['product_name']) ?></td>
                                            <td class="text-end custom-success"><?= (int) $item['total_quantity'] ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<script>
    window.DASHBOARD_DATA = <?= json_encode([
        'expStatus' => $expStatus,
    ]) ?>;
</script>
<script src="public/js/dashboard.js"></script>
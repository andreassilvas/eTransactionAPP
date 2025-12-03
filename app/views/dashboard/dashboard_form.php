<?php

$stockSummary = $stockSummary ?? [];
$stockByCategory = $stockByCategory ?? [];
$criticalProducts = $criticalProducts ?? [];
$expStatus = $expStatus ?? [];


// compute health percentage
$total = (int) ($stockSummary['total_products'] ?? 0);
$ok = (int) ($stockSummary['ok_stock'] ?? 0);
$health = $total > 0 ? round($ok * 100 / $total) : 0;
?>

<div class="card py-4 px-5">
    <div class="card-body">
        <h3 class="card-title custom-color-i">Tableau de Bord - Inventaire & Expéditions</h3>
        <p class="card-text mb-4 custom-color-i">Vue globale du stock, des alertes et des expéditions.</p>

        <!-- TOP KPIs -->
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <a href="<?= BASE_URL . '/produits-en-stock' ?>" class="text-decoration-none">
                    <div class="card card-kpi shadow-sm card-clickable">
                        <div class="card-body custom-color-i">
                            <span class="label">Produits en stock</span>
                            <h2><?= (int) $stockSummary['total_products'] ?></h2>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-6 col-md-3">
                <div class="card card-kpi shadow-sm">
                    <div class="card-body">
                        <span class="label custom-warning">Faible stock(1–5)</span>
                        <h2 class="custom-warning"><?= (int) $stockSummary['low_stock'] ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card card-kpi shadow-sm">
                    <div class="card-body">
                        <span class="label text-danger">Rupture</span>
                        <h2 class="text-danger"><?= (int) $stockSummary['out_of_stock'] ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <a href="<?= BASE_URL . '/produits-livre' ?>" class="text-decoration-none">
                    <div class="card card-kpi shadow-sm card-clickable">
                        <div class="card-body">
                            <span class="label text-success">Produits expédiés</span>
                            <h2 class="text-success"><?= $totalExpeditions ?></h2>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <div class="row g-3 mb-3">
            <!-- Column 1: Stock health donut -->
            <div class="col-md-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header text-center fw-semibold custom-header-dashboard">
                        Santé globale du stock
                    </div>
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <canvas id="chart-health"></canvas>
                        <div class="mt-3 text-muted small">
                            <?= $health ?>% des produits ont un stock &gt; 5.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Column 2: Stock by category -->
            <div class="col-md-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header text-center fw-semibold custom-header-dashboard">
                        Stock total de produits par catégorie
                    </div>
                    <div class="card-body">
                        <canvas id="chart-category" height="220"></canvas>
                    </div>
                </div>
            </div>

            <!-- Column 3: Critical products -->
            <div class="col-md-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header text-center fw-semibold custom-header-dashboard">
                        Produits critiques (alerte)
                    </div>
                    <div class="card-body">
                        <?php if (empty($criticalProducts)): ?>
                            <div class="text-muted small">Aucune alerte de stock pour le moment.</div>
                        <?php else: ?>
                            <div class="list-group list-group-flush">
                                <?php foreach ($criticalProducts as $p): ?>
                                    <?php
                                    $isOut = ((int) $p['stock'] === 0);
                                    $badgeClass = $isOut ? 'danger' : 'warning-custom';
                                    $badgeText = $isOut ? 'RUPTURE' : 'Faible stock';
                                    ?>
                                    <div class="list-group-item d-flex align-items-center gap-3">
                                        <div class="avatar-circle flex-shrink-0">
                                            <?= strtoupper(substr($p['name'], 0, 1)) ?>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="fw-semibold custom-color-i"><?= htmlspecialchars($p['name']) ?></div>
                                            <div class="small text-muted">
                                                <span>Catégorie : <?= htmlspecialchars($p['category'] ?? '—') ?></span><br>
                                                <span>Stock:
                                                    <strong class="badge text-bg-<?= $badgeClass ?>"><?= (int) $p['stock'] ?>
                                                    </strong>
                                                </span>
                                            </div>
                                        </div>
                                        <span class="badge text-bg-<?= $badgeClass ?>"><?= $badgeText ?></span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    window.DASHBOARD_DATA = <?= json_encode([
        'healthPct' => $health,
        'stockByCategory' => $stockByCategory,
        'expStatus' => $expStatus,
    ]) ?>;
</script>
<script src="public/js/dashboard.js"></script>
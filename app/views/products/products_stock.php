<?php
$stockSummary = $stockSummary ?? [];
$totalProducts = count($products);
$totalStock = 0;
$totalInventoryValue = 0;
$lowStock = 5;
$categoryAgg = []; // ['categorie' => ['stock' => int, 'value' => float]]

foreach ($products as $product) {
    $stock = (int) ($product['stock'] ?? 0);
    $price = (float) ($product['price'] ?? 0);

    $totalStock += $stock;
    $totalInventoryValue += $stock * $price;

    $categorie = $product['category'] ?? 'Autre';

    if (!isset($categoryAgg[$categorie])) {
        $categoryAgg[$categorie] = ['stock' => 0, 'value' => 0];
    }
    $categoryAgg[$categorie]['stock'] += $stock;
    $categoryAgg[$categorie]['value'] += $stock * $price;
}

$categoryLabels = array_keys($categoryAgg);
$categoryStocks = array_column($categoryAgg, 'stock');

$lowStockCount = 0;
foreach ($products as $product) {
    $stock = (int) ($product['stock'] ?? 0);
    if ($stock > 0 && $stock <= $lowStock) {
        $lowStockCount++;
    }
}
?>

<div class="card px-3">
    <div class="card-body">
        <h3 class="card-title custom-color-i pb-5 pt-4">Produits en stock</h3>

        <div class="row g-3 mb-4">
            <div class="col-md-2">
                <div class="card card-kpi shadow-sm">
                    <div class="card-body custom-color-i">
                        <h6 class="text-muted mb-1">Nombre de produits</h6>
                        <h3 class="mb-0"><?= $totalProducts ?></h3>
                    </div>
                </div>
            </div>

            <div class="col-md-2">
                <div class="card card-kpi shadow-sm">
                    <div class="card-body custom-color-i">
                        <h6 class="text-muted mb-1">Stock total</h6>
                        <h3 class="mb-0"><?= $totalStock ?></h3>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card card-kpi shadow-sm">
                    <div class="card-body custom-color-i">
                        <h6 class="text-muted mb-1">Valeur de l’inventaire</h6>
                        <h3 class="mb-0">
                            <?= number_format($totalInventoryValue, 2, ',', ' ') ?> $
                        </h3>
                    </div>
                </div>
            </div>

            <div class="col-md-2">
                <div class="card card-kpi shadow-sm">
                    <div class="card-body custom-warning">
                        <h6 class="mb-1">Produits en faible stock (≤ <?= $lowStock ?>)</h6>
                        <h3 class="mb-0"><?= $lowStockCount ?></h3>
                    </div>
                </div>
            </div>

            <div class="col-md-2">
                <div class="card card-kpi shadow-sm">
                    <div class="card-body custom-warning">
                        <h6 class="mb-1 text-danger">Rupture stock</h6>
                        <h3 class="text-danger"><?= (int) $stockSummary['out_of_stock'] ?></h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Graph + Tableau -->
        <div class="row g-3">
            <!-- Graphique stock par catégorie -->
            <div class="col-lg-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <span class="fw-semibold custom-color-i">Stock par catégorie</span>
                    </div>
                    <div class="card-body">
                        <canvas id="stockByCategory"></canvas>
                    </div>
                </div>
            </div>

            <!-- Tableau des produits -->
            <div class="col-lg-8">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <span class="fw-semibold custom-color-i">Produits en stock</span>
                        <div class="input-group input-group-sm" style="max-width: 260px;">
                            <span class="input-group-text"><i class="fa fa-search"></i></span>
                            <input id="searchInput" type="text" class="form-control"
                                placeholder="Filtrer par nom, marque...">
                        </div>
                    </div>
                    <div class="card-body table-responsive">
                        <table class="table table-hover align-middle mb-0" id="productsTable">
                            <thead class="custom-health-table">
                                <tr>
                                    <th>ID</th>
                                    <th>Nom</th>
                                    <th>Catégorie</th>
                                    <th>Marque</th>
                                    <th>Modèle</th>
                                    <th class="text-end">Prix</th>
                                    <th class="text-end">Stock</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($products as $p): ?>
                                    <?php
                                    $stock = (int) ($p['stock'] ?? 0);
                                    $isOutStock = $stock === 0;
                                    $isLowStock = $stock > 0 && $stock <= $lowStock;
                                    ?>
                                    <tr>
                                        <td><?= htmlspecialchars($p['id']) ?></td>
                                        <td><?= htmlspecialchars($p['name']) ?></td>
                                        <td><?= htmlspecialchars($p['category']) ?></td>
                                        <td><?= htmlspecialchars($p['brand']) ?></td>
                                        <td><?= htmlspecialchars($p['model']) ?></td>
                                        <td class="text-end">
                                            <?= number_format((float) $p['price'], 2, ',', ' ') ?> $
                                        </td>
                                        <td class="text-end"><?= $stock ?></td>
                                        <td>
                                            <?php if ($isOutStock): ?>
                                                <span class="badge text-bg-danger">
                                                    Rupture stock
                                                </span>
                                            <?php elseif ($isLowStock): ?>
                                                <span class="badge text-bg-warning-custom border border-warning-subtle">
                                                    Faible stock
                                                </span>
                                            <?php else: ?>
                                                <span class="badge text-bg-success">
                                                    OK
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    window.DASHBOARD_PROD_DATA = {
        categoryLabels: <?= json_encode($categoryLabels, JSON_UNESCAPED_UNICODE) ?>,
        categoryStocks: <?= json_encode($categoryStocks) ?>
    };
    console.log("Injected data:", window.DASHBOARD_PROD_DATA);
</script>
<script src="public/js/products/productsDashboard.js"></script>
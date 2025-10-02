<div class="container my-5 px-0">
    <div class="card">
        <div class="card-body">

            <?php if (!empty($products)): ?>
                <table class="table table-striped table-bordered mb-5">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Catégorie</th>
                            <th>Marque</th>
                            <th>Modèle</th>
                            <th>Prix</th>
                            <th>Stock</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product): ?>
                            <tr>
                                <td><?= htmlspecialchars($product['id']) ?></td>
                                <td><?= htmlspecialchars($product['name']) ?></td>
                                <td><?= htmlspecialchars($product['category']) ?></td>
                                <td><?= htmlspecialchars($product['brand']) ?></td>
                                <td><?= htmlspecialchars($product['model']) ?></td>
                                <td><?= htmlspecialchars($product['price']) ?>$</td>
                                <td><?= htmlspecialchars($product['stock']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="alert alert-info">Aucune Produits trouvée pour le moment.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
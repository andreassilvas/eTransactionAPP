<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Ensure $transactions is defined
$transactions = $transactions ?? [];
?>

<div class="container my-5">
    <?php if (!empty($transactions)): ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Description</th>
                    <th>Crédit</th>
                    <th>Débit</th>
                    <th>Solde</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($transactions as $tx): ?>
                    <tr>
                        <td><?= htmlspecialchars($tx['transaction_date']) ?></td>
                        <td><?= htmlspecialchars($tx['description']) ?></td>
                        <td><?= htmlspecialchars($tx['credit']) ?></td>
                        <td><?= htmlspecialchars($tx['debit']) ?></td>
                        <td><?= htmlspecialchars($tx['balance']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="alert alert-info">Aucune transaction trouvée pour le moment.</p>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
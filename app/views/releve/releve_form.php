<?php
// if (session_status() === PHP_SESSION_NONE) {
//     session_start();
// }

// Ensure $transactions is defined
$transactions = $transactions ?? [];
?>

<div class="container my-5">
    <div class="card">
        <div class="card-body">
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
                        <?php foreach ($transactions as $transaction): ?>
                            <tr>
                                <td><?= htmlspecialchars($transaction['transaction_date']) ?></td>
                                <td><?= htmlspecialchars($transaction['description']) ?></td>
                                <td><?= htmlspecialchars($transaction['credit']) ?>$</td>
                                <td><?= htmlspecialchars($transaction['debit']) ?>$</td>
                                <td><?= htmlspecialchars($transaction['balance']) ?>$</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="alert alert-info">Aucune transaction trouvée pour le moment.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
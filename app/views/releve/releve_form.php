<?php
$name = htmlspecialchars($_SESSION['client_name'] ?? '');


$transactions = $transactions ?? [];

//pagination variables
$perPage = 10; // number of transactions per page
$total = count($transactions);
$totalPages = ceil($total / $perPage);

// Current page (from query string), default = 1
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
if ($page < 1)
    $page = 1;
if ($page > $totalPages)
    $page = $totalPages;

// Slice transactions for current page
$start = ($page - 1) * $perPage;
$transactionsPage = array_slice($transactions, $start, $perPage);
?>

<div class="container my-5 px-0">
    <div class="card">
        <div class="card-body">
            <h6 class="card-title custom-color-d pb-3">Client : <?= $name ?></h6>
            <?php if (!empty($transactions)): ?>
                <table class="table table-striped table-bordered mb-5">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Description</th>
                            <th class="text-center">Crédit</th>
                            <th class="text-center">Débit</th>
                            <th class="text-center">Solde</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($transactions as $transaction): ?>
                            <tr>
                                <td><?= htmlspecialchars($transaction['transaction_date']) ?></td>
                                <td><?= htmlspecialchars($transaction['description']) ?></td>
                                <td class="text-end"><?= htmlspecialchars($transaction['credit']) ?>$</td>
                                <td class="text-end"><?= htmlspecialchars($transaction['debit']) ?>$</td>
                                <td class="text-end"><?= htmlspecialchars($transaction['balance']) ?>$</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <nav aria-label="Page navigation ">
                    <ul class="pagination justify-content-end">
                        <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                            <a class="page-link" href="?page=<?= $page - 1 ?>">Previous</a>
                        </li>

                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                                <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>

                        <li class="page-item <?= ($page >= $totalPages) ? 'disabled' : '' ?>">
                            <a class="page-link" href="?page=<?= $page + 1 ?>">Next</a>
                        </li>
                    </ul>
                </nav>
            <?php else: ?>
                <p class="alert alert-info">Aucune transaction trouvée pour le moment.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
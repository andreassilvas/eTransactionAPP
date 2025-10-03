<?php
function renderTableWithPagination($data, $headers, $fields, $perPage = 5, $customClass = '', $clientName = null, $solde = null)
{

    $clientName = $clientName ? htmlspecialchars($clientName) : null;

    $total = count($data);
    $totalPages = ceil($total / $perPage);

    $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
    if ($page < 1)
        $page = 1;
    if ($page > $totalPages)
        $page = $totalPages;

    $start = ($page - 1) * $perPage;
    $pageData = array_slice($data, $start, $perPage);
    ?>

    <div class="container my-5 px-0">
        <div class="card">
            <div class="card-body">
                <?php if ($clientName): ?>
                    <h6 class="card-title custom-color-d pb-3">Client : <?= $clientName ?></h6>
                <?php endif; ?>
                <?php if ($solde): ?>
                    <h6 class="card-title <?= $solde > 0 ? 'custom-color-g' : 'custom-color-e' ?> pb-3">
                        Solde disponible : <?= htmlspecialchars($solde) ?>
                    </h6>
                <?php endif; ?>
                <?php if (!empty($pageData)): ?>
                    <table class="table table-striped table-bordered mb-5">
                        <thead>
                            <tr>
                                <?php foreach ($headers as $header): ?>
                                    <th <?= !empty($header['style']) ? "style=\"{$header['style']}\"" : '' ?>>
                                        <?= htmlspecialchars($header['text']) ?>
                                    </th>
                                <?php endforeach; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pageData as $row): ?>
                                <tr>
                                    <?php foreach ($fields as $key => $formatter): ?>
                                        <?php
                                        // Decide class based on field name
                                        $tdClass = '';
                                        if (in_array($key, ['credit', 'debit', 'balance'])) {
                                            $tdClass = 'text-end'; // Bootstrap class for right-align
                                        }
                                        ?>
                                        <td class="<?= $tdClass ?>">
                                            <?php
                                            $value = $row[$key] ?? '';
                                            echo is_callable($formatter)
                                                ? $formatter($value, $row)
                                                : htmlspecialchars($value);
                                            ?>
                                        </td>
                                    <?php endforeach; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>

                    </table>

                    <!-- Pagination -->
                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-end">
                            <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                                <a class="page-link" href="?page=<?= $page - 1 ?>">Previous</a>
                            </li>
                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <li class="page-item">
                                    <a class="page-link <?= $customClass ?> <?= ($i == $page) ? 'active' : '' ?>"
                                        href="?page=<?= $i ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>
                            <li class="page-item <?= ($page >= $totalPages) ? 'disabled' : '' ?>">
                                <a class="page-link" href="?page=<?= $page + 1 ?>">Next</a>
                            </li>
                        </ul>
                    </nav>

                <?php else: ?>
                    <p class="alert alert-info">Aucune donnée trouvée pour le moment.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php
}
?>
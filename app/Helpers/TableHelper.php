<?php

/**
 * Affiche un tableau paginé avec le nom du client et le solde optionnels.
 *
 * @param array $data        Les données à afficher dans le tableau.
 * @param array $headers     Tableau des en-têtes avec 'text' et optionnellement 'style'.
 * @param array $fields      Tableau de clés ou de callbacks pour afficher chaque ligne.
 * @param int $perPage       Nombre de lignes par page (défaut : 5).
 * @param string $customClass Classe CSS personnalisée pour les liens de pagination.
 * @param string|null $clientName Nom du client à afficher au-dessus du tableau (optionnel).
 * @param float|null $solde  Solde à afficher au-dessus du tableau (optionnel).
 */
function renderTableWithPagination($data, $cardHeader, $headers, $fields, $perPage = 5, $customClass = '', $clientName = null, $solde = null)
{
    // Échapper le nom du client pour un affichage sécurisé
    $clientName = $clientName ? htmlspecialchars($clientName) : null;

    // Pagination
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


    <div class="card px-3">
        <?php if ($cardHeader): ?>
            <div>
                <h3 class="card-title custom-color-i pb-3 pt-4"><?= $cardHeader ?></h3>
            </div>
        <?php endif; ?>

        <div class="card-body">
            <?php if ($clientName): ?>
                <h6 class="card-title custom-color-d pb-3 text-end">Client : <?= $clientName ?></h6>
            <?php endif; ?>
            <?php if ($solde): ?>
                <h6 class="card-title <?= $solde > 0 ? 'custom-color-g' : 'custom-color-e' ?> pb-3 text-end mb-3">
                    Solde disponible : <?= htmlspecialchars($solde) ?>
                </h6>
            <?php endif; ?>
            <?php if (!empty($pageData)): ?>
                <div class="table-responsive">
                    <table class="table mb-5">
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
                                        // Déterminer la classe en fonction du nom du champ
                                        $tdClass = '';
                                        if (in_array($key, ['credit', 'debit', 'balance'])) {
                                            $tdClass = 'text-end';
                                        }
                                        ?>
                                        <td class="<?= $tdClass ?>">
                                            <?php
                                            $value = $row[$key] ?? '';
                                            if (is_callable($formatter)) {
                                                // Convertit les chaînes vides ou les valeurs nulles en 0 avant le formatage
                                                if ($value === '' || $value === null) {
                                                    $value = 0.00;
                                                }
                                                echo $formatter($value, $row);
                                            } else {
                                                echo htmlspecialchars($value ?? '');
                                            }
                                            ?>
                                        </td>
                                    <?php endforeach; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>

                    </table>
                </div>
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
    <?php
}
?>
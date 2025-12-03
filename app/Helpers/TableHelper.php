<?php

/**
 * Affiche un tableau compatible DataTables (sans pagination PHP).
 *
 * @param array  $data        Les données à afficher dans le tableau.
 * @param string $tableId     ID HTML du tableau (pour DataTables JS).
 * @param string $cardHeader  Titre de la card.
 * @param array  $headers     Tableau des en-têtes avec 'text' et optionnellement 'style'.
 * @param array  $fields      Tableau de clés ou de callbacks pour afficher chaque ligne.
 * @param string $customClass Classe CSS personnalisée (facultatif).
 * @param string|null $clientName Nom du client au-dessus du tableau (optionnel).
 * @param float|null  $solde  Solde au-dessus du tableau (optionnel).
 * @param string|null $name   Bouton d'action  
 */
function renderDataTable(
    array $data,
    string $tableId,
    ?string $cardHeader,
    array $headers,
    array $fields,
    string $customClass = '',
    ?string $clientName = null,
    ?float $solde = null,
    ?string $useBtn = null
) {
    $clientName = $clientName ? htmlspecialchars($clientName) : null;
    ?>
    <div class="card px-3 <?= htmlspecialchars($customClass) ?>">

        <div class="card-body">
            <?php if ($cardHeader): ?>
                <h3 class="card-title custom-color-i pb-3 pt-4"><?= htmlspecialchars($cardHeader) ?></h3>
            <?php endif; ?>
            <?php if ($useBtn): ?>
                <div class="d-flex justify-content-start align-items-center mb-2 mt-5">
                    <?php
                    $btnText = "Ajouter un utilisateur";
                    $btnId = "ajouterUtilisateur";
                    $btnBg = '#5C5CFF';
                    $btnBorder = '#5C5CFF';
                    $btnTextColor = '#fff';
                    $btnHoverBg = '#00738A';
                    $btnHoverBorder = '#00738A';
                    include __DIR__ . '/../Views/components/base_button.php';
                    ?>
                </div>
            <?php endif; ?>

            <?php if ($clientName): ?>
                <h6 class="card-title custom-color-d pb-3">
                    Client : <?= $clientName ?>
                </h6>
            <?php endif; ?>

            <?php if ($solde !== null): ?>
                <h6 class="card-title <?= $solde > 0 ? 'custom-color-g' : 'custom-color-e' ?> pb-3 mb-3">
                    Solde disponible :
                    <?= htmlspecialchars(number_format($solde, 2, ',', ' ')) ?> $
                </h6>
            <?php endif; ?>


            <?php if (!empty($data)): ?>
                <div class="table-responsive">
                    <table id="<?= htmlspecialchars($tableId) ?>" class="table mb-5 py-4">
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
                            <?php foreach ($data as $row): ?>
                                <tr>
                                    <?php foreach ($fields as $key => $formatter): ?>
                                        <?php
                                        $tdClass = '';
                                        if (in_array($key, ['credit', 'debit', 'balance'], true)) {
                                            $tdClass = 'text-start';
                                        }
                                        $value = $row[$key] ?? '';
                                        ?>
                                        <td class="<?= $tdClass ?>">
                                            <?php
                                            if (is_callable($formatter)) {
                                                if ($value === '' || $value === null) {
                                                    $value = 0.00;
                                                }
                                                echo $formatter($value, $row);
                                            } else {
                                                echo htmlspecialchars((string) $value);
                                            }
                                            ?>
                                        </td>
                                    <?php endforeach; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="alert alert-info mb-0">Aucune donnée trouvée pour le moment.</p>
            <?php endif; ?>
        </div>
    </div>
    <?php
}

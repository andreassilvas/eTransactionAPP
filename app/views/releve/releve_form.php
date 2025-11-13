<?php
require_once __DIR__ . '/../../Helpers/TableHelper.php';

$headers = [
    ['text' => 'Date', 'style' => ''],
    ['text' => 'Description', 'style' => ''],
    ['text' => 'Crédit', 'style' => ''],
    ['text' => 'Débit', 'style' => ''],
    ['text' => 'Balance', 'style' => '']
];

$fields = [
    'transaction_date' => null,
    'description' => null,
    'credit' => fn($val) => (is_numeric($val) ? number_format((float) $val, 2) . ' $' : ''),
    'debit' => fn($val) => is_numeric($val)
        ? ((float) $val > 0 ? '-' : '') . number_format((float) $val, 2) . ' $'
        : '0.00 $',
    'balance' => fn($val) => (is_numeric($val) ? number_format((float) $val, 2) . ' $' : ''),
];

if (!empty($transactions)) {
    $firstTransaction = reset($transactions);
    $solde = (float) ($firstTransaction['balance'] ?? 0);
} else {
    $solde = null;
}

renderDataTable($transactions, "tbl-releve-bancaire", "Relevé Bancaire", $headers, $fields, 'releve-pagination', $_SESSION['client_name'] ?? '', $solde);

?>
<script src="public/js/tables/dataTableReleve.js"></script>
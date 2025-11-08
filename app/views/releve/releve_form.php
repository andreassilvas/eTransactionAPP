<?php
require_once __DIR__ . '/../../Helpers/TableHelper.php';



$headers = [
    ['text' => 'Date', 'style' => ''],
    ['text' => 'Description', 'style' => ''],
    ['text' => 'Crédit', 'style' => 'text-align: end;'],
    ['text' => 'Débit', 'style' => 'text-align: end;'],
    ['text' => 'Balance', 'style' => 'text-align: end;']
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
    $soldeValue = $firstTransaction['balance'] ?? 0;
    $solde = number_format((float) $soldeValue, 2) . ' $';
}
;



renderTableWithPagination($transactions, $headers, $fields, 5, 'custom', $_SESSION['client_name'] ?? '', $solde);



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
    'credit' => fn($val) => number_format($val, 2) . ' ' . '$',
    'debit' => fn($val) => number_format($val, 2) . ' ' . '$',
    'balance' => fn($val) => number_format($val, 2) . ' ' . '$',
];

if (!empty($transactions)) {
    $firstTransaction = reset($transactions);
    $solde = $firstTransaction['balance'] ?? 0;
    $solde = number_format($solde, 2) . ' ' . '$';
}

renderTableWithPagination($transactions, $headers, $fields, 5, '', $_SESSION['client_name'] ?? '', $solde);

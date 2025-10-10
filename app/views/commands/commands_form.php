<?php
require __DIR__ . '/../../Helpers/TableHelper.php';

// Définition des en-têtes du tableau
$headers = [
    ['text' => 'Date', 'style' => 'width:110px;'],
    ['text' => 'Exp. Id', 'style' => 'width:100px;'],
    ['text' => 'Acheteur', 'style' => 'width:130px;'],
    ['text' => 'Courriel acheteur', 'style' => 'width:180px;'],
    ['text' => 'Produits', 'style' => ''],
    ['text' => 'Montant', 'style' => 'width:95px;'],
    ['text' => 'Payé', 'style' => ''],
    ['text' => 'Payer par', 'style' => 'width:110px;'],
];

// Définition des champs à afficher pour chaque ligne
$fields = [
    'expedition_date' => null,
    'expedition_id' => null,
    'expedition_name' => fn($v, $row) => $v . ' ' . $row['expedition_lastname'],
    'expedition_email' => null,
    'products' => null,
    'payment_amount' => fn($val) => number_format($val, 2) . ' $',
    'payment_status' => fn($val) => ucfirst($val),
    'payment_method' => null,
];

// Appel de la fonction pour afficher le tableau paginé
renderTableWithPagination($commands, $headers, $fields, 5, 'custom', $_SESSION['client_name'] ?? '');


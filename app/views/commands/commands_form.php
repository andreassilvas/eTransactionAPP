<?php
require __DIR__ . '/../../Helpers/TableHelper.php';

// Définition des en-têtes du tableau
$headers = [
    ['text' => 'Date', 'style' => ''],
    ['text' => 'Exp. Id', 'style' => ''],
    ['text' => 'Acheteur', 'style' => ''],
    ['text' => 'Courriel acheteur', 'style' => ''],
    ['text' => 'Produits', 'style' => ''],
    ['text' => 'Montant', 'style' => ''],
    ['text' => 'Payé', 'style' => ''],
    ['text' => 'Payer par', 'style' => ''],
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
renderDataTable($commands, "tbl-commands", "Historique des commandes", $headers, $fields, 'commands-pagination', $_SESSION['client_name'] ?? '');

?>
<script src="public/js/tables/dataTableCommands.js"></script>
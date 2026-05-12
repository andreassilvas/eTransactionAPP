<?php
require __DIR__ . '/../../Helpers/TableHelper.php';

// Définition des en-têtes du tableau
$headers = [
    ['text' => 'Date d\'activité', 'style' => ''],
    ['text' => 'ID exp.', 'style' => ''],
    ['text' => 'Acheteur', 'style' => ''],
    ['text' => 'Courriel', 'style' => ''],
    ['text' => 'Produits', 'style' => ''],
    ['text' => 'Montant', 'style' => ''],
    ['text' => 'Payé', 'style' => ''],
    ['text' => 'Payer par', 'style' => ''],
];

// Définition des champs à afficher pour chaque ligne
$fields = [
    'expedition_date' => function ($val) {

        if (empty($val)) {
            return '';
        }

        $date = new DateTime(
            $val,
            new DateTimeZone('UTC')
        );

        $date->setTimezone(
            new DateTimeZone('America/Montreal')
        );

        $formatter = new IntlDateFormatter(
            'fr_CA',
            IntlDateFormatter::NONE,
            IntlDateFormatter::NONE,
            'America/Montreal',
            null,
            "d MMM yyyy - HH'h'mm"
        );

        return mb_strtolower(
            $formatter->format($date),
            'UTF-8'
        );
    },
    'expedition_id' => null,
    'expedition_name' => fn($v, $row) => $v . ' ' . $row['expedition_lastname'],
    'expedition_email' => null,
    'products' => null,
    'payment_amount' => fn($val) => number_format($val, 2) . ' $',
    'payment_status' => fn($val) => ucfirst($val),
    'payment_method' => null,
];

// Appel de la fonction pour afficher le tableau paginé
renderDataTable($commands, "tbl-commands", "Activité du compte", $headers, $fields, 'commands-pagination', $_SESSION['client_name'] ?? '');

?>
<script src="/public/js/tables/dataTableCommands.js"></script>
<script src="/public/js/authGuard.js"></script>
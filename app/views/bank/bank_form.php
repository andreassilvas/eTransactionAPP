<?php
require_once __DIR__ . '/../../Helpers/TableHelper.php';

$headers = [
    ['text' => 'Date de transaction', 'style' => ''],
    ['text' => 'Utilisateur', 'style' => ''],
    ['text' => 'Type de transaction', 'style' => ''],
    ['text' => 'Description', 'style' => ''],
    ['text' => 'Crédit', 'style' => 'text-align: end;'],
    ['text' => 'Débit', 'style' => 'text-align: end;']
];

$fields = [
    'transaction_date' => function ($val) {

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

        $display = mb_strtolower(
            $formatter->format($date),
            'UTF-8'
        );

        $sort = $date->format('YmdHis');

        return "
    <span style='display:none'>
        {$sort}
    </span>
    {$display}
";
    },
    'user_fullname' => null,
    'transaction_type' => null,
    'description' => null,
    'credit' => fn($val) => (is_numeric($val) ? number_format((float) $val, 2) . ' $' : ''),
    'debit' => fn($val) => is_numeric($val)
        ? ((float) $val > 0 ? '-' : '') . number_format((float) $val, 2) . ' $'
        : '0.00 $',
];

$solde = $companyBalance ?? 0;

renderDataTable($transactions, "tbl-releve-bancaire", "Relevé Bancaire", $headers, $fields, 'releve-pagination', $_SESSION['client_name'] ?? '', $solde);

?>
<script src="/public/js/tables/dataTableReleve.js"></script>
<script src="/public/js/authGuard.js"></script>
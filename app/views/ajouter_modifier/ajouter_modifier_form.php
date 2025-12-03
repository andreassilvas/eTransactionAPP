<?php
require_once __DIR__ . '/../../Helpers/TableHelper.php';

$headers = [
    ['text' => 'ID', 'style' => ''],
    ['text' => 'Nom', 'style' => ''],
    ['text' => 'Catégorie', 'style' => ''],
    ['text' => 'Marque', 'style' => ''],
    ['text' => 'Modèle', 'style' => ''],
    ['text' => 'Prix', 'style' => ''],
    ['text' => 'Stock', 'style' => '']
];
$fields = [
    'id' => null,
    'name' => null,
    'category' => null,
    'brand' => null,
    'model' => null,
    'price' => fn($v) => number_format($v, 2) . ' $',
    'stock' => null,
];

renderDataTable($products, $headers, $fields, 5, 'custom-prod');


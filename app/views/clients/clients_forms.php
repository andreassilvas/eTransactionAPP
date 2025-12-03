<?php
require __DIR__ . '/../../Helpers/TableHelper.php';


// Définition des en-têtes du tableau
$headers = [
    ['text' => 'Id', 'style' => ''],
    ['text' => 'Prénom', 'style' => ''],
    ['text' => 'Nom', 'style' => ''],
    ['text' => 'Téléphone', 'style' => ''],
    ['text' => 'Ext', 'style' => ''],
    ['text' => 'Courrier', 'style' => ''],
    ['text' => 'Adresse', 'style' => ''],
    ['text' => 'Ville', 'style' => ''],
    ['text' => 'Province', 'style' => ''],
    ['text' => 'Code Postal', 'style' => ''],
    ['text' => 'Mot de passe', 'style' => ''],
    ['text' => 'Action', 'style' => 'text-align:center;'],
];

// Définition des champs à afficher pour chaque ligne
$fields = [
    'id' => null,
    'name' => null,
    'lastname' => null,
    'phone' => null,
    'extention' => null,
    'email' => null,
    'address' => null,
    'city' => null,
    'province' => null,
    'postcode' => null,
    'password' => fn($val) => '••••••',
    '_action' => null,
];

renderDataTable($clients, "tbl-client", 'Gestion des Utilisateurs', $headers, $fields, 'client-pagination', null, null, 'add-user');
?>

<script src="public/js/validation-lib.js"></script>
<script src="public/js/user/userApi.js"></script>
<script src="public/js/geoApi.js"></script>
<script src="public/js/user/userActions.js"></script>
<script src="public/js/user/userManagement.js"></script>
<script src="public/js/tables/dataTableClients.js"></script>
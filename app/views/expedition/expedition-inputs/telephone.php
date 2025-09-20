<?php
$id = "telephone";
$label = "*Numéro de téléphone";
$name = "phone";
$type = "text";
$input_value = $input_value = $_POST['phone'] ?? '';
$describedby = "numHelp";
$describe = "Nous vous appellerons uniquement en cas de problèmes avec la commande.";
include __DIR__ . "/../../layouts/base-form-inputs/input_info.php";
<?php
$id = "mois_exp";
$label = "*Mois d'expiration";
$name = "expiry_month";
$input_value = $input_value = $_POST['mois_exp'] ?? '';
$type = "text";
include __DIR__ . "/../../layouts/base-form-inputs/input.php";
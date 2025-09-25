<?php
$id = "mois_exp";
$label = "*Mois d'expiration";
$name = "expiry_month";
$input_value = $_POST['mois_exp'] ?? '';
$type = "text";
$maxlength = "2";
$placeholder = "01";
$custom_class = "placeholder-style";

include __DIR__ . "/../../layouts/base-form-inputs/input.php";
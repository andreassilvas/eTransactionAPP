<?php
$id = "nro_carte";
$label = "*Numero de la carte";
$name = "card_number";
$maxlength = 16;
$input_value = $input_value = $_POST['nro_carte'] ?? '';
$type = "text";
$autocomplete = "off";

include __DIR__ . "/../../layouts/base-form-inputs/input.php";
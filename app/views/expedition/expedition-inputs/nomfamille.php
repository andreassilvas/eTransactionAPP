<?php
$id = "nomFamille";
$label = "*Nom de famille";
$name = "lastname";
$input_value = $input_value = $_POST['lastname'] ?? '';
$type = "text";
include __DIR__ . "/../../layouts/base-form-inputs/input.php";
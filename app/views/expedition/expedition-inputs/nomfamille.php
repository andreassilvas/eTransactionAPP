<?php
$id = "nomFamille";
$label = "*Nom de famille";
$name = "lastname";
$input_value = $_POST['lastname'] ?? '';
$type = "text";
$minlength = "2";
$autocomplete = "name";
include __DIR__ . "/../../layouts/base-form-inputs/input.php";
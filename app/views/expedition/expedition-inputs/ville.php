<?php
$id = "city";
$label = "*Ville";
$name = "city";
$input_value = $input_value = $_POST['city'] ?? '';
$type = "text";
$autocomplete = "address-level2";
$pattern = null;
$minlength = "2";
$required = true;
include __DIR__ . "/../../layouts/base-form-inputs/input.php";
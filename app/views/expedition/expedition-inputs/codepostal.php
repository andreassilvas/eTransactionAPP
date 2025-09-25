<?php
$id = "postCode";
$label = "*Code postal";
$name = "postcode";
$input_value = $_POST['postcode'] ?? '';
$type = "text";
$autocomplete = "postal-code";
$placeholder = "H1H 1H1";
$pattern = "^[A-Za-z]\d[A-Za-z][ ]?\d[A-Za-z]\d$";
$maxlength = "7";
$required = true;
$minlength = null;

$custom_class = "placeholder-style";

include __DIR__ . "/../../layouts/base-form-inputs/input.php";
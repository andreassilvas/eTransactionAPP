<?php
$id = "address";
$label = "*Adresse";
$name = "address";
$input_value = $_POST['address'] ?? '';
$type = "text";
$minlength = "10";
$maxlength = "100";
$required = true;
$placeholder = "";
$pattern = null;
$autocomplete = "address-line1";
include __DIR__ . "/../../layouts/base-form-inputs/input.php";
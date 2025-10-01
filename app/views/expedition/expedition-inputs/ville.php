<?php
$id = "city";
$label = "*Ville";
$name = "city";
$input_value = $_POST['city'] ?? '';
$type = "text";
$autocomplete = "address-level2";
$pattern = null;
$minlength = "2";
$required = true;
$pattern = "^[A-Za-zÀ-ÿ\- ]+$";
$maxlength = "50";
include __DIR__ . "/../../layouts/base-form-inputs/input.php";
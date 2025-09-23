<?php
$id = "extention";
$label = "Ext. (facultatif)";
$name = "extention";
$placeholder = null;
$input_value = $input_value = $_POST['extention'] ?? '';
$type = "text";
$minlength = 6;
$required = false;
$pattern = null;
$autocomplete = "tel";
include __DIR__ . "/../../layouts/base-form-inputs/input.php";
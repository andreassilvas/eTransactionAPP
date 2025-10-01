<?php
$id = "exp_date";
$label = "*Date d'expiration";
$name = "expiry_date";
$input_value = $_POST['expiry_date'] ?? '';
$type = "text";
$maxlength = "5";
$placeholder = "01/28";
$custom_class = "placeholder-style";
$pattern = "^(0[1-9]|1[0-2])\/\d{2}$";
$required = true;

include __DIR__ . "/../../layouts/base-form-inputs/input.php";
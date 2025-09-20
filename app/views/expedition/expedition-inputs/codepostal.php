<?php
$id = "postCode";
$label = "*Code postal";
$name = "postcode";
$input_value = $input_value = $_POST['postcode'] ?? '';
$type = "text";
include __DIR__ . "/../../layouts/base-form-inputs/input.php";
<?php
$id = "card_name";
$label = "*Nom sur la carte";
$name = "card_name";
$input_value = $_POST['card_name'] ?? '';
$type = "text";
$maxlength = "50";
$placeholder = "";
$required = true;
$pattern = "[A-Za-zÀ-ÿ\s\-]{2,50}";

include __DIR__ . "/../../layouts/base-form-inputs/input.php";
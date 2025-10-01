<?php
$id = "nro_cvv";
$label = "*Numero CVV";
$name = "cvv";
$input_value = $_POST['nro_cvv'] ?? '';
$type = "text";
$icon = "assets/images/cvv.webp";
$maxlength = "4";
$required = true;
$pattern = "\d{3,4}";
$placeholder = "123";
$custom_class = "placeholder-style";

include __DIR__ . "/../../layouts/base-form-inputs/input_with_image.php";


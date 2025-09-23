<?php
$id = "nro_cvv";
$label = "Numero CVV";
$name = "nro_cvv";
$input_value = $_POST['nro_cvv'] ?? '';
$type = "text";
$icon = "assets/images/cvv.webp";

include __DIR__ . "/../../layouts/base-form-inputs/input_with_image.php";


<?php
$id = "email_adresse";
$label = "Adresse courriel";
$type = "email";
$name = "email";
$input_value = $_POST['email'] ?? '';
include __DIR__ . "/../../layouts/base-form-inputs/input_info.php";
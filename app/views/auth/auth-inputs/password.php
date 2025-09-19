<?php
$id = "password";
$label = "Mot de passe";
$type = "password";
$name = "password";
$input_value = $_POST['password'] ?? '';
include __DIR__ . "/../../layouts/base-form-inputs/input_password.php";


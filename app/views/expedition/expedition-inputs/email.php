<?php
$id = "email_adresse";
$label = "*Adresse courriel";
$type = "email";
$describedby = "emailHelp";
$input_value = $_POST['email'] ?? '';
$describe = "Nous enverrons les mises à jour de commande cette adresse courriel.";
include __DIR__ . "/../../layouts/base-form-inputs/input_info.php";
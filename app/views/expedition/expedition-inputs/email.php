<?php
$id = "email_adresse";
$label = "*Adresse courriel";
$name = "email";
$type = "email";
$describedby = "emailHelp";
$input_value = $_POST['email'] ?? '';
$autocomplete = "email";
$placeholder = "exemple@courriel.com";
$describe = "Nous enverrons les mises à jour de commande cette adresse courriel.";
$required = true;
$maxlength = "80";

$custom_class = "placeholder-style";


include __DIR__ . "/../../layouts/base-form-inputs/input_info.php";
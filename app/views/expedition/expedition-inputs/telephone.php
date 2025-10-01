<?php
$id = "telephone";
$label = "*Numéro de téléphone";
$name = "phone";
$type = "tel";
$pattern = "^\(?\d{3}\)?[ ]?\d{3}[ ]?\d{4}$";
$placeholder = "(514) 123 4567";
$minlength = 10;
$maxlength = 12;
$required = true;
$autocomplete = "tel";
$input_value = $_POST['phone'] ?? '';
$describedby = "numHelp";
$describe = "Nous vous appellerons uniquement en cas de problèmes avec la commande.";
$custom_class = "placeholder-style";
include __DIR__ . "/../../layouts/base-form-inputs/input_info.php";

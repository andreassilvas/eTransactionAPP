<?php
$id = "annee_exp";
$label = "Annee d'expiration";
$name = "annee_exp";
$input_value = $input_value = $_POST['annee_exp'] ?? '';
$type = "text";

include __DIR__ . "/../../layouts/base-form-inputs/input.php";
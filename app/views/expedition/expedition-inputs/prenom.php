<?php
$id = "prenom";
$label = "*Prenom";
$name = "prenom";
$type = "text";
$input_value = $_POST['prenom'] ?? '';
include __DIR__ . "/../../layouts/base-form-inputs/input.php";
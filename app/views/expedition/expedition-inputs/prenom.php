<?php
$id = "name";
$label = "*Prenom";
$name = "name";
$type = "text";
$placeholder = null;
$input_value = $_POST['name'] ?? '';
$minlength = "2";
$autocomplete = "given-name";
$pattern = "^[A-Za-zÀ-ÿ\- ]+$";
$required = true;
include __DIR__ . "/../../layouts/base-form-inputs/input.php";
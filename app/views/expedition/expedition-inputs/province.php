<?php
$id = "province";
$name = "province";
$label = "*Province";
$placeholder = "-sélectionner-";
$options = [
    "1" => "One",
    "2" => "Two",
    "3" => "Three",
    "4" => "Three",
    "5" => "Three",
];
$selected = $_POST['province'] ?? null; // optional preselection
include __DIR__ . "/../../layouts/base-form-inputs/input_select.php";

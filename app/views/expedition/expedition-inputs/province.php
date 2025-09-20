<?php
$id = "province";
$name = "province";
$label = "*Province";
$placeholder = "-sélectionner-";
$options = [
    "Alberta" => "Alberta",
    "British Columbia" => "Colombie-Britannique",
    "Prince Edward Island" => "Île-du-Prince-Édouard",
    "Manitoba" => "Manitoba",
    "New Brunswick" => "Nouveau-Brunswick",
    "Nova Scotia" => "Nouvelle-Écosse",
    "Ontario" => "Ontario",
    "Québec" => "Québec",
    "Saskatchewan" => "Saskatchewan",
    "Terre-Neuve-et-Labrador" => "Terre-Neuve-et-Labrador",
];
$selected = $_POST['province'] ?? null; // optional preselection
include __DIR__ . "/../../layouts/base-form-inputs/input_select.php";

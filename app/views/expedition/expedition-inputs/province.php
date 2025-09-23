<?php
$id = "province";
$name = "province";
$label = "*Province";
$placeholder = "-sélectionner-";
$autocomplete = "address-level1";
$minlength = null;

$options = [
    "Alberta" => "AB",
    "British Columbia" => "BC",
    "Prince Edward Island" => "PE",
    "Manitoba" => "MB",
    "New Brunswick" => "NB",
    "Nova Scotia" => "NS",
    "Ontario" => "ON",
    "Québec" => "QC",
    "Saskatchewan" => "SK",
    "Newfoundland and Labrador" => "NL",
];
$selected = $_POST['province'] ?? null; // optional preselection
include __DIR__ . "/../../layouts/base-form-inputs/input_select.php";

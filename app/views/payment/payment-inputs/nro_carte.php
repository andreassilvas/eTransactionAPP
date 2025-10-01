<?php
$id = "nro_carte";
$label = "*Numero de la carte";
$name = "card_number";
$maxlength = 19;
$input_value = $input_value = $_POST['nro_carte'] ?? '';
$type = "text";
$required = true;
$pattern = "";
$autocomplete = "off";

include __DIR__ . "/../../layouts/base-form-inputs/input.php";
?>

<script>
    const cardInput = document.getElementById('<?= $id ?>');

    cardInput.addEventListener('input', (e) => {
        // Remove all non-digit characters
        let value = e.target.value.replace(/\D/g, '');

        // Add space every 4 digits
        value = value.match(/.{1,4}/g)?.join(' ') || '';

        e.target.value = value;
    });
</script>
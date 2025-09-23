<?php
$pattern = $pattern ?? null;
$minlength = $minlength ?? null;
$maxlength = $maxlength ?? null;
$required = $required ?? false;
$placeholder = $placeholder ?? null;
?>

<div class="mb-3">
    <label for="<?= $id ?>"><?= $label ?></label>
    <input type="<?= $type ?>" class="form-control rounded-3 <?= $custom_class ?? '' ?>" id="<?= $id ?>"
        name="<?= $name ?>" value="<?= $input_value ?? '' ?>" minlength="<?= $minlength ?>"
        autocomplete="<?= $autocomplete ?? 'off' ?>" <?php if (!empty($pattern))
                echo 'pattern="' . $pattern . '"'; ?>
        <?php if (!empty($minlength))
            echo 'minlength="' . $minlength . '"'; ?> <?php if (!empty($required))
                       echo 'required'; ?> <?php if (!empty($placeholder))
                              echo 'placeholder="' . $placeholder . '"'; ?>>
</div>
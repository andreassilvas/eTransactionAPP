<?php
$pattern = $pattern ?? null;
$minlength = $minlength ?? null;
$maxlength = $maxlength ?? null;
$required = $required ?? false;
$placeholder = $placeholder ?? null;
?>

<div class="form-floating mb-3 position-relative">

    <input type="<?= $type ?>"
        class="form-control rounded-3 <?= $custom_class ?? '' ?> <?= isset($icon) ? 'pe-5' : '' ?>" id="<?= $id ?>"
        name="<?= $name ?>" value="<?= $input_value ?? '' ?>" placeholder="<?= $placeholder ?? ' ' ?>" <?php if (!empty($maxlength))
                      echo 'maxlength="' . $maxlength . '"'; ?>>

    <label for="<?= $id ?>">
        <?= $label ?>
    </label>

    <?php if (isset($icon)): ?>
        <img src="<?= $icon ?>" alt="icon" class="position-absolute"
            style="top: 50%; right: 35px; transform: translateY(-50%); z-index: 5;" width="30">
    <?php endif; ?>

</div>
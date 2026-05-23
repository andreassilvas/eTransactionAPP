<?php
$pattern = $pattern ?? null;
$minlength = $minlength ?? null;
$maxlength = $maxlength ?? null;
$required = $required ?? false;
$placeholder = $placeholder ?? null;
?>

<div class="form-floating mb-3">

    <input type="<?= $type ?>" class="form-control rounded-3 <?= $custom_class ?? '' ?>" id="<?= $id ?>"
        name="<?= $name ?>" value="<?= $input_value ?? '' ?>" autocomplete="<?= $autocomplete ?? 'off' ?>"
        placeholder="<?= $placeholder ?? ' ' ?>" <?php if (!empty($pattern))
                echo 'pattern="' . $pattern . '"'; ?> <?php if (!empty($minlength))
                           echo 'minlength="' . $minlength . '"'; ?> <?php if (!empty($maxlength))
                                      echo 'maxlength="' . $maxlength . '"'; ?> <?php if (!empty($required))
                                                 echo 'required'; ?>>

    <label for="<?= $id ?>"><?= $label ?></label>
</div>
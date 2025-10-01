<?php
$pattern = $pattern ?? null;
$minlength = $minlength ?? null;
$maxlength = $maxlength ?? null;
$required = $required ?? false;
?>

<label for="<?= $id ?>"><?= $label ?></label>
<input type="<?= $type ?>" value="<?= $input_value ?>" name="<?= $name ?>" placeholder="<?= $placeholder ?>"
    class="form-control rounded-3 <?= $custom_class ?? '' ?>" id="<?= $id ?>" aria-describedby="<?= $describedby ?>"
    autocomplete="<?= $autocomplete ?? 'off' ?>" <?php if (!empty($pattern))
            echo 'pattern="' . $pattern . '"'; ?> <?php if (!empty($minlength))
                       echo 'minlength="' . $minlength . '"'; ?> <?php if (!empty($required))
                                  echo 'required'; ?>
    <?php if (!empty($maxlength))
        echo 'maxlength="' . $maxlength . '"'; ?>>


<?php if (!empty($describe ?? '')): ?>
    <div id="<?= $describedby ?>" class="custom-form-text"><?= $describe ?></div>
<?php endif; ?>
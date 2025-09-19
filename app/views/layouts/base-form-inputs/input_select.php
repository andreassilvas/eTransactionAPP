<label for="<?= $id ?>"><?= $label ?></label>
<select class="form-select" id="<?= $id ?>" name="<?= $name ?>">
    <?php if (!empty($placeholder)): ?>
        <option value="" selected><?= $placeholder ?></option>
    <?php endif; ?>

    <?php foreach ($options as $optValue => $optText): ?>
        <option value="<?= $optValue ?>" <?= (isset($selected) && $selected == $optValue) ? 'selected' : '' ?>>
            <?= $optText ?>
        </option>
    <?php endforeach; ?>
    <?php unset($optValue, $optText); ?>
</select>
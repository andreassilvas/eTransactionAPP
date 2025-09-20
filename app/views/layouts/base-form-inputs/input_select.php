<label for="<?= $id ?>"><?= $label ?></label>
<select class="form-select" id="<?= $id ?>" name="<?= $name ?>">
    <?php if (!empty($placeholder)): ?>
        <option value=""><?= $placeholder ?></option>
    <?php endif; ?>

    <?php foreach ($options as $optValue => $optText): ?>
        <option value="<?= htmlspecialchars($optText) ?>" <?= (isset($selected) && $selected == $optText) ? 'selected' : '' ?>>
            <?= htmlspecialchars($optText) ?>
        </option>
    <?php endforeach; ?>
    <?php unset($optValue, $optText); ?>
</select>
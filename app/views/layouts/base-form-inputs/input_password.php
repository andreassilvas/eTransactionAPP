<div class="form-floating mb-3">
    <input type="<?= $type ?>" class="form-control" name="<?= $name ?>" id="<?= $id ?>" required placeholder=" "
        value="<?= $input_value ?? '' ?>">

    <label for="<?= $id ?>">
        <?= $label ?>
    </label>
</div>
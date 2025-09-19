<div class="mb-3 position-relative">
    <label for="<?= $id ?>"><?= $label ?></label>

    <!-- Input with right icon inside -->
    <input type="<?= $type ?>" class="form-control rounded-3 <?= isset($icon) ? 'pe-5' : '' ?>" id="<?= $id ?>"
        name="<?= $name ?>" value="<?= $input_value ?? '' ?>" placeholder="<?= $placeholder ?? '' ?>">

    <?php if (isset($icon)): ?>
        <img src="<?= $icon ?>" alt="icon" class="position-absolute"
            style="top: 70%; right: 10px; transform: translateY(-50%);" width="30">
    <?php endif; ?>
</div>
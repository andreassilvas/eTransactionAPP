<label for="<?= $id ?>"><?= $label ?></label>
<input type="<?= $type ?>" value="<?= $input_value ?>" name="<?= $name ?>" class="form-control rounded-3"
    id="<?= $id ?>" aria-describedby="<?= $describedby ?>">

<?php if (!empty($describe ?? '')): ?>
    <div id="<?= $describedby ?>" class="form-text"><?= $describe ?></div>
<?php endif; ?>
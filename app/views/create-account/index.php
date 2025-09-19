<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="container mt-4">
    <h2>Créer un compte</h2>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form action="/eTransactionAPP/public/create-account/store" method="POST">
        <?php 'account-inputs/email.php'; ?>
        <?php 'account-inputs/prenom.php'; ?>
        <?php 'account-inputs/nomfamille.php'; ?>

        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Confirmer Password</label>
            <input type="password" name="confirmPassword" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Créer le compte</button>
    </form>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>
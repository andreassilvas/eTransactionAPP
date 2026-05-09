<div class="container pt-5 pb-5">
    <?php include __DIR__ . '/../layouts/popup_window_session_expired.php'; ?>

    <h2 class="fw-light custom-color-accent mb-4">
        Heureux de vous revoir
        <span class="custom-color-accent fw-semibold" id="clientName"></span>
    </h2>
</div>

<div class="row justify-content-center mt-3">
    <div class="col-sm-4">
        <?php include __DIR__ . '/../admin/inventaire.php'; ?>
    </div>

    <div class="col-sm-4 mb-3 mb-sm-0">
        <?php include __DIR__ . '/../admin/bank.php'; ?>
    </div>
    <div class="col-sm-4">
        <?php include __DIR__ . '/../admin/client.php'; ?>
    </div>
</div>

<script src="/public/js/adminConnexion/adminAPI.js"></script>
<script src="/public/js/authGuard.js"></script>
<script src="/public/js/modal/sessionWillExpire.js"></script>
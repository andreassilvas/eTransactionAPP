<div id="loginModal" class="modal fade" data-has-error="<?= !empty($error) ? 'true' : 'false' ?>" tabindex="-1"
    aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content px-3 py-3">
            <div class="modal-header" style="border-bottom: none;">
                <h5 class="modal-title" id="loginModalLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="<?= BASE_URL ?>/login" method="POST" id="loginForm">
                    <div class="mb-3">
                        <?php include __DIR__ . '/auth-inputs/email.php' ?>
                    </div>
                    <div class="mb-3">
                        <?php include __DIR__ . '/auth-inputs/password.php' ?>
                    </div>
                    <div class="my-3 small">
                        <?php include __DIR__ . '/../layouts/base-form-inputs/policy_statement.php' ?>
                    </div>

                    <!-- hidden field to tell the controller where to go -->
                    <input type="hidden" name="redirect" value="expedition">

                    <?php
                    $btnText = "Se connecter";
                    $btnType = "submit";
                    include __DIR__ . '/../components/base_button.php';
                    ?>
                </form>
                <?php if (!empty($error)): ?>
                    <script>
                        document.addEventListener('DOMContentLoaded', () => {
                            const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
                            loginModal.show(); // keep modal open
                        });
                    </script>
                    <div class="alert alert-danger mt-2">
                        <?= htmlspecialchars($error); ?>
                    </div>

                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<script src="js/loginModal.js"></script>
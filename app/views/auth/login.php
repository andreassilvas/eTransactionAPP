<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content px-3 py-3">
            <div class="modal-header" style="border-bottom: none;">
                <h5 class="modal-title" id="loginModalLabel">Se connecter</h5>
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
                    <!-- Fixed error container -->
                    <div id="loginErrorContainer" style="min-height: 40px;"></div>
                    <?php
                    $btnText = "Se connecter";
                    $btnBg = "#2E3133";
                    $btnBorder = "#2E3133";
                    $btnType = "submit";
                    include __DIR__ . '/../components/base_button.php';
                    ?>
                </form>
                <?php if (!empty($loginError)): ?>
                    <div class="alert alert-danger mt-2">
                        <?= htmlspecialchars($loginError); ?>
                    </div>
                    <script>
                        document.addEventListener('DOMContentLoaded', () => {
                            const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
                            loginModal.show(); // automatically open modal on error
                        });
                    </script>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<script src="js/loginModal.js"></script>
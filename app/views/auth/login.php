<?php
if (session_status() === PHP_SESSION_NONE)
    session_start();
?>
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content px-3 py-3">
            <div class="modal-header" style="border-bottom: none;">
                <h5 class="modal-title" id="loginModalLabel">Connexion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="/eTransactionAPP/public/login" method="POST" id="loginForm">
                    <div class="mb-3">
                        <?php include __DIR__ . '/auth-inputs/email.php' ?>
                    </div>
                    <div class="mb-3">
                        <?php include __DIR__ . '/auth-inputs/password.php' ?>
                    </div>
                    <div class="my-3 small">
                        <?php include __DIR__ . '/../layouts/base-form-inputs/policy_statement.php' ?>
                    </div>
                    <?php
                    $btnText = "Se connecter";
                    $btnType = "submit";
                    include __DIR__ . '/../components/base_button.php';
                    ?>
                </form>
                <?php if (isset($_SESSION['login_error'])): ?>
                    <script>
                        document.addEventListener('DOMContentLoaded', () => {
                            const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
                            loginModal.show(); // keep modal open
                        });
                    </script>
                    <div class="alert alert-danger mt-2">
                        <?= htmlspecialchars($_SESSION['login_error']); ?>
                    </div>
                    <?php unset($_SESSION['login_error']); ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
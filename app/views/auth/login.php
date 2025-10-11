<!-- Modal de connexion -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content px-3 py-3">

            <!-- En-tête du modal -->
            <div class="modal-header" style="border-bottom: none;">
                <h5 class="modal-title" id="loginModalLabel">Se connecter</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Corps du modal -->
            <div class="modal-body">
                <form action="<?= BASE_URL ?>/login" method="POST" id="loginForm">
                    <!-- Champ email -->
                    <div class="mb-3">
                        <?php include __DIR__ . '/auth-inputs/email.php' ?>
                    </div>

                    <!-- Champ mot de passe -->
                    <div class="mb-3">
                        <?php include __DIR__ . '/auth-inputs/password.php' ?>
                    </div>

                    <!-- Déclaration de politique / consentement -->
                    <div class="my-3 small">
                        <?php include __DIR__ . '/../layouts/base-form-inputs/policy_statement.php' ?>
                    </div>

                    <!-- Conteneur pour afficher les erreurs côté client -->
                    <div id="loginErrorContainer" style="min-height: 40px;"></div>

                    <!-- Bouton de soumission -->
                    <?php
                    $btnText = "Se connecter";
                    $btnBg = "#2E3133";
                    $btnBorder = "#2E3133";
                    $btnType = "submit";
                    include __DIR__ . '/../components/base_button.php';
                    ?>
                </form>

                <!-- Affichage des erreurs côté serveur -->
                <?php if (!empty($loginError)): ?>
                    <div class="alert alert-danger mt-2">
                        <?= htmlspecialchars($loginError); ?>
                    </div>
                    <script>
                        document.addEventListener('DOMContentLoaded', () => {
                            // Ouvre automatiquement le modal si une erreur est présente
                            const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
                            loginModal.show();
                        });
                    </script>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Script spécifique au modal de connexion -->
<script src="public/js/loginModal.js"></script>
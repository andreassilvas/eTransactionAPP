<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-header" style="background-color:#2B3035; color: #fff;">
            <h3 class="mb-0"> Transfert de fonds</h3>
        </div>

        <div class="card-body">
            <form id="transferForm">
                <div class="form-floating mb-3">
                    <input type="number" step="0.01" min="0.01" class="form-control" id="amount" required
                        placeholder="0.00">
                    <label for="amount">Montant</label>
                </div>
                <div class="form-floating mb-3">
                    <select class="form-select" id="operation_type" aria-label="Méthode de transfert" required>
                        <option value="" selected disabled>Choisir une méthode</option>
                        <option value="versement">Versement</option>
                    </select>
                    <label for="operation_type">Méthode de transfert</label>
                </div>
                <div class="form-floating mb-5">
                    <textarea class="form-control no-resize" placeholder="Leave a comment here" id="description"
                        rows="3"></textarea>
                    <label for="description">Description</label>
                </div>
                <div>
                    <?php
                    $btnText = "Envoyer";
                    $btnBg = '#fff';
                    $btnBorder = '#2B3035';
                    $btnTextColor = '#2B3035';
                    $btnHoverBg = '#2B3035';
                    $btnHoverBorder = '#2B3035';
                    $btnType = 'submit';
                    include __DIR__ . '/../components/base_button.php';
                    ?>
                </div>
            </form>
            <div id="transferMessage" class="mt-3"></div>
        </div>
    </div>
</div>
<script type="module" src="/public/js/transfert/transfertUI.js"></script>
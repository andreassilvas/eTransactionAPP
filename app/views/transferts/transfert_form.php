<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-header" style="background-color:#2B3035; color: #fff;">
            <h3 class="mb-0"> Transfert de fonds</h3>
        </div>

        <div class="card-body">
            <form id="transferForm">

                <div class="mb-3">
                    <label for="amount" class="form-label">Montant</label>
                    <input type="number" step="0.01" min="0.01" class="form-control" id="amount" required
                        placeholder="0.00">
                </div>
                <select class="form-select" id="operation_type" required>
                    <option value="versement">
                        Versement
                    </option>
                </select>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" rows="3"></textarea>
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
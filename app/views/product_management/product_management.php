<div class="card px-3">
    <div class="card-body">
        <div class="d-flex justify-content-start align-items-center mb-5 mt-4">
            <?php
            $btnText = "Ajouter un nouveau produit";
            $btnId = "ajouterProduit";
            $btnBg = '#00738A';
            $btnBorder = '#00738A';
            $btnTextColor = '#fff';
            $btnHoverBg = '#7F00FF';
            $btnHoverBorder = '#7F00FF';
            include __DIR__ . '/../components/base_button.php';
            ?>
        </div>
        <table id="tbl" class="table table-hover w-100 pt-4 pb-4">
            <thead class="custom-gestion-product-table">
                <tr>
                    <th>Id</th>
                    <th>Nom</th>
                    <th>Catégorie</th>
                    <th>Marque</th>
                    <th>Modèle</th>
                    <th>Spécifications</th>
                    <th>Prix</th>
                    <th>Stock</th>
                    <th>Garantie</th>
                    <th>Support</th>
                    <th>Fournisseur</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>
<script src="public/js/validation/gestionProductsValidation.js"></script>
<script src="public/js/products/productApi.js"></script>
<script src="public/js/products/productAction.js"></script>

<script>
    const API = "<?= rtrim(BASE_URL, '/') ?>/gestion-des-produits";
</script>
<script src="public/js/products/gestionDesProduits.js"></script>
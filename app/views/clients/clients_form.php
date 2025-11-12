<div class="container-fluid py-4">
    <div class="card px-3">
        <div class="card-body">
            <div class="d-flex justify-content-start align-items-center mb-4 mt-4">
                <?php
                $btnText = "Ajouter un utilisateur";
                $btnId = "ajouterUtilisateur";
                $btnBg = '#00738A';
                $btnBorder = '#00738A';
                $btnTextColor = '#fff';
                $btnHoverBg = '#00738A';
                $btnHoverBorder = '#00738A';
                include __DIR__ . '/../components/base_button.php';
                ?>
            </div>
            <table id="tbl" class="table table-hover w-100 pt-4 pb-4">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Prénom</th>
                        <th>Nom</th>
                        <th>Téléphone</th>
                        <th>Ext</th>
                        <th>Email</th>
                        <th>Adresse</th>
                        <th>Ville</th>
                        <th>Province</th>
                        <th>Code postal</th>
                        <th>Mot de passe</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>
<script src="public/js/validation-lib.js"></script>
<script src="public/js/clientApi.js"></script>
<script src="public/js/geoApi.js"></script>
<script src="public/js/clientTableView.js"></script>

<script>
    const API = "<?= rtrim(BASE_URL, '/') ?>/gestion-utilisateurs";
</script>
<script src="public/js/clientManagement.js"></script>
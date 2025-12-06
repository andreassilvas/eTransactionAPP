<div class="card px-3">
    <div class="card-body">
        <h3 class="card-title custom-color-i pt-4">Administration des utilisateurs</h3>
        <p class="card-text mb-5 custom-color-i">Gestion des utilisateurs, ajouter, modifier et supprimer des
            utilisateurs.</p>
        <div class="d-flex justify-content-start align-items-center mb-5 mt-4">
            <?php
            $btnText = "Ajouter un utilisateur";
            $btnId = "ajouterUtilisateur";
            $btnBg = '#5C5CFF';
            $btnBorder = '#5C5CFF';
            $btnTextColor = '#fff';
            $btnHoverBg = '#7F00FF';
            $btnHoverBorder = '#7F00FF';
            include __DIR__ . '/../components/base_button.php';
            ?>
        </div>
        <table id="tbl" class="table table-hover w-100 pt-4 pb-4">
            <thead class="custom-gestion-utilisateurs-table">
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
<script src="public/js/validation-lib.js"></script>
<script src="public/js/user/userApi.js"></script>
<script src="public/js/user/geoApi.js"></script>
<script src="public/js/user/userAction.js"></script>

<script>
    const API = "<?= rtrim(BASE_URL, '/') ?>/gestion-utilisateurs";
</script>
<script src="public/js/user/userManagement.js"></script>
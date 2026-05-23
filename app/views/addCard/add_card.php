<div class="container py-4">
    <div class="card shadow-sm">
        <div class="card-header" style="background-color:#2B3035; color: #fff;">
            <h3 class="mb-0">Ajouter une carte</h3>
        </div>

        <div class="card-body">

            <form id="addCardForm">
                <div class="row">
                    <div class="col-12 pad-left">
                        <div class="form-floating mb-4">
                            <select class="form-select" id="to_client_id" aria-label="Méthode de transfert" required>
                                <option value="" selected disabled></option>
                            </select>
                            <label for="to_client_id">Sélectionner un utilisateur</label>
                        </div>
                        <div>
                            <p class="custom-color-d">*Indiquer les renseignements obligatoires</p>
                        </div>

                        <div class="row mb-4">
                            <div class="col">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="card_name"
                                        placeholder="nom du titulaire de la carte">
                                    <label for="card_name">*Nom sur la carte</label>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="nro_carte"
                                        placeholder="numéro de la carte" length="19" maxlength="19">
                                    <label for="nro_carte">*Numéro de la carte</label>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="postCode" placeholder="code postal"
                                        length="7" maxlength="7">
                                    <label for="postCode">*Code postal</label>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="exp_date"
                                        placeholder="date d'expiration">
                                    <label for="exp_date">*Date d'expiration</label>
                                </div>
                            </div>
                        </div>
                        <div class="row g-3 mb-5">
                            <div class="col-sm">
                                <div class="form-floating mb">
                                    <input type="text" class="form-control" id="nro_cvv" placeholder="cvv" length="4"
                                        maxlength="4">
                                    <label for="nro_cvv">*CVV</label>
                                </div>
                            </div>
                            <div class="col-sm">
                                <div class="form-floating">
                                    <select class="form-select" id="card_type" aria-label="Méthode de transfert"
                                        required>
                                        <option value="" selected disabled></option>
                                        <option value="Visa">Visa</option>
                                        <option value="MasterCard">Mastercard</option>
                                    </select>
                                    <label for="card_type">*Type de carte</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row gap-4">
                        <div class="col-1">
                            <?php
                            $btnId = "add_card";
                            $btnText = "Envoyer";
                            $btnBg = '#494D4F';
                            $btnBorder = '#494D4F';
                            $btnTextColor = '#fff';
                            $btnHoverBg = '#2B3035';
                            $btnHoverBorder = '#2B3035';
                            $btnType = 'submit';
                            include __DIR__ . '/../components/base_button.php';
                            ?>
                        </div>
                        <div class="col-sm">
                            <?php
                            $btnId = "annuler_form";
                            $btnText = "Annuler";
                            $btnBg = '#fff';
                            $btnBorder = '#9C223A';
                            $btnTextColor = '#9C223A';
                            $btnHoverBg = '#A1222F';
                            $btnHoverBorder = '#A1222F';
                            $btnType = 'button';
                            include __DIR__ . '/../components/base_button.php';
                            ?>
                        </div>
                    </div>

                </div>

            </form>

            <div id="transferMessage" class="mt-3"></div>
        </div>
    </div>
</div>
<script type="module" src="/public/js/addCardToClient/add_cardUI.js"></script>
<script type="module" src="/public/js/addCardToClient/validationAddCard.js"></script>
<script src="/public/js/authGuard.js"></script>
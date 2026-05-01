<div class="col-9 pad-left pt-4">
    <div class="card mb-4 rounded-4 px-3">
        <div class="card-body mt-3">

            <div class="d-flex justify-content-start align-items-center pb-4">
                <div class="d-flex align-items-center custom-success fw-bold">
                    <h5>Transaction réussie</h5>
                    <i class="fa-sharp fa-solid fa-circle-check fa-lg ms-2 custom-success"></i>
                </div>
            </div>

            <!-- CLIENT -->
            <h5 class="card-title custom-color-b mb-2">Informations sur le client</h5>
            <div class="card mb-4 rounded-4">
                <div class="card-body">
                    <ul class="list-group custom-color-dark" id="client-info">
                        <li class="list-group-item border-0">Chargement...</li>
                    </ul>
                </div>
            </div>

            <!-- EXPEDITION -->
            <h5 class="card-title custom-color-b">Informations sur l'expédition</h5>
            <div class="card mb-4 rounded-4">
                <div class="card-body">
                    <ul class="list-group" id="expedition-info">
                        <li class="list-group-item border-0">Chargement...</li>
                    </ul>
                </div>
            </div>

            <!-- COMMANDES -->
            <h5 class="card-title custom-color-b">Résumé de la commande</h5>

            <div class="card mb-1 rounded-top-4">
                <div class="card-body">
                    <ul class="list-group" id="order-items">
                        <li class="list-group-item border-0">Chargement...</li>
                    </ul>
                </div>
            </div>

            <div class="card mb-0 rounded-bottom-4">
                <div class="card-body">
                    <div id="order-totals">Chargement...</div>
                </div>
            </div>

        </div>
    </div>
</div>

<script type="module" src="/public/js/paymentSuccess/verificationSuccessUI.js"></script>
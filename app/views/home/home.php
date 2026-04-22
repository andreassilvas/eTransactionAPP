<div class="row g-3">

    <div class="col-sm-4">
        <div class="card rounded-4">
            <div class="card-body mt-3 text-center">
                <img src="public/assets/images/server4.webp" alt="server image 1"
                    class="d-inline-block align-text-top mx-auto d-block">

                <div class="gap-2 cart-component position-relative">
                    <!-- Add to cart -->
                    <button class="btn btn-primary" id="add-to-cart" aria-label="ajoute au panier">
                        <i class="fa-solid fa-cart-shopping"></i> Ajouter au panier
                    </button>
                    <!-- Stepper -->
                    <div class="input-group stepper d-none" style="width: 130px;">
                        <button class="btn btn-outline-secondary to-add-to-cart" id="decrement">🗑</button>
                        <input type="text" class="form-control text-center value" id="quantity" value="1" readonly>
                        <button class="btn btn-outline-secondary" id="increment">+</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-4">
        <div class="card rounded-4">
            <div class="card-body mt-3">
                <img src="public/assets/images/server5.webp" alt="server image 2"
                    class="d-inline-block align-text-top mx-auto d-block">
            </div>
        </div>
    </div>

    <div class="col-sm-4">
        <div class="card rounded-4">
            <div class="card-body mt-3">
                <img src="public/assets/images/server6.webp" alt="server image 3"
                    class="d-inline-block align-text-top mx-auto d-block">
            </div>
        </div>
    </div>
</div>
<script src="public/js/home/cart.js"></script>
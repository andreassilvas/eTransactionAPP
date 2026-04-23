<div class="row g-3">
    <?php foreach ($products as $product): ?>
        <div class="col-sm-4">
            <div class="card rounded-4">

                <div class="card-body mt-3 text-center">
                    <img src="public/assets/images/<?= $product['image'] ?>" alt="<?= $product['name'] ?? '' ?>"
                        class="d-inline-block align-text-top mx-auto d-block">

                    <div class="cart-component d-flex justify-content-center gap-2 pt-2"
                        data-product-id="<?= $product['id'] ?>">

                        <button class="btn btn-primary add-to-cart">
                            <i class="fa-solid fa-cart-shopping"></i> Ajouter au panier
                        </button>
                        <!-- Stepper -->
                        <div class="input-group stepper d-none" style="width: 130px;">
                            <button class="btn btn-outline-secondary decrement">🗑</button>
                            <input type="text" class="form-control text-center value" id="quantity" value="1" readonly>
                            <button class="btn btn-outline-secondary increment">+</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<script src="public/js/home/cart.js"></script>
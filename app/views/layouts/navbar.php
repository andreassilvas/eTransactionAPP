<nav class="navbar navbar-expand-lg bg-dark border-bottom border-body px-5 fixed-top">
    <div class="container-fluid">
        <!-- Brand -->
        <a class="navbar-brand" href="#">
            <img src="public/assets/images/brand.webp" alt="Logo eTransaction" height="50"
                class="d-inline-block align-text-top">
        </a>

        <!-- Toggler button (for mobile) -->
        <button class="navbar-toggler btn-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation"
            style="background-color:#94AEE3">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Collapsible menu -->
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
            <ul class="nav nav-underline gap-4 home-navbar">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" style="color:#94AEE3" href="#">Produits</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" style="color:#94AEE3" href="<?= BASE_URL ?>/documentation">
                        Documentation
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" data-bs-toggle="modal" data-bs-target="#loginModal"
                        data-source="admin" style="color:#94AEE3" href="#">Connexion admin</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-links" data-source="client" style="color:#ffc107" href="#">
                        <i class="fa-solid fa-cart-shopping" style="color:#ffc107"></i>
                        <span id="cart-count" class="badge bg-warning text-dark">0</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
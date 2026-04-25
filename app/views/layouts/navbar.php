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
            <ul class="nav nav-underline gap-4">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" style="color:#94AEE3">Produits</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" style="color:#94AEE3">Documentation</a>
                </li>
                <li class="nav-item">
                    <button class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#loginModal"
                        data-source="admin">Connexion admin</button>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-links" data-bs-toggle="modal" data-bs-target="#loginModal"
                        data-source="client" style="color:#31D2F2">
                        <i class="fa-solid fa-cart-shopping" style="color:#ffc107"></i>
                        <span id="cart-count" class="badge bg-warning text-dark">0</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
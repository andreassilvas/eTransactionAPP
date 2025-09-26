<nav class="navbar bg-dark border-bottom border-body px-5">
    <div class="container-fluid">
        <!-- Brand -->
        <a class="navbar-brand" href="#">
            <img src="assets/images/brand.webp" alt="Logo eTransaction" height="50"
                class="d-inline-block align-text-top">
        </a>

        <!-- Navbar links -->
        <div>
            <ul class="nav justify-content-end nav-underline">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="#" style="color:#0066FF">Produits</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-links" href="#" data-bs-toggle="modal" data-bs-target="#loginModal"
                        data-redirect="releve" data-title="Accéder à mes relevés">Relevé</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link nav-links" href="#" data-bs-toggle="modal" data-bs-target="#loginModal"
                        data-redirect="expedition" data-title="Connexion à l’expédition">Expedition</a>
                </li>
                <li class="nav-item">
                    <button class="btn btn-outline-success" type="submit" data-bs-toggle="modal"
                        data-bs-target="#createAccount">Créer un compte</button>
                </li>
                <!-- Cart icon -->
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="fa-solid fa-cart-shopping"></i>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<!-- Include Login Modal -->
<!-- <?php include 'login/modal_login.php'; ?>
<?php include 'create-account/create_account_form.php'; ?> -->
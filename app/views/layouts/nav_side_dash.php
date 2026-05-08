<?php
function isActive($path)
{
    return strpos($_SERVER['REQUEST_URI'], $path) !== false;
}
?>

<ul class="nav flex-column mt-5 pt-4">

    <!-- Dashboard -->
    <li class="nav-item mb-2">
        <a class="nav-link sidebar-link <?= isActive('/tableau-de-bord') ? 'active-link' : '' ?>"
            href="<?= BASE_URL . '/tableau-de-bord' ?>">

            <i class="fa-solid fa-chart-line me-2"></i>
            Tableau de bord
        </a>
    </li>

    <!-- Produits -->
    <li class="nav-item mb-2">

        <button type="button" class="btn sidebar-btn w-100 text-start nav-link" data-bs-toggle="collapse"
            data-bs-target="#productsMenu" aria-expanded="true" aria-controls="productsMenu">

            <i class="fa-solid fa-box me-2"></i>
            Gestion des Produits
        </button>

        <div class="collapse show ps-3 <?= $productsOpen ? 'show' : '' ?>" id="productsMenu">
            <a class="nav-link sidebar-link small <?= isActive('/produits-en-stock') ? 'active-link' : '' ?>"
                href="<?= BASE_URL . '/produits-en-stock' ?>">
                Produits en stock
            </a>

            <a class="nav-link sidebar-link small <?= isActive('/produits-livre') ? 'active-link' : '' ?>"
                href="<?= BASE_URL . '/produits-livre' ?>">
                Produits expédiés
            </a>

            <a class="nav-link sidebar-link small <?= isActive('/administration-des-produits') ? 'active-link' : '' ?>"
                href="<?= BASE_URL . '/administration-des-produits' ?>">
                Administration produits
            </a>
        </div>

    </li>

    <!-- Analyse des ventes-->
    <li class="nav-item">

        <button type="button" class="btn sidebar-btn w-100 text-start nav-link" data-bs-toggle="collapse"
            data-bs-target="#usersMenu" aria-expanded="true" aria-controls="usersMenu">

            <i class="fa-solid fa-users me-2"></i>
            Analyse des ventes
        </button>

        <!-- <div class="collapse show ps-3 <?= $usersOpen ? 'show' : '' ?>" id="usersMenu">
            <a class="nav-link sidebar-link small <?= isActive('') ? 'active-link' : '' ?>" href="<?= BASE_URL . '' ?>">
                Ventes par client
            </a>
        </div> -->

    </li>
</ul>
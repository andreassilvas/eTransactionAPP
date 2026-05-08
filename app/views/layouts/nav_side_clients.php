<?php
function isActive($path)
{
    return strpos($_SERVER['REQUEST_URI'], $path) !== false;
}
?>
<ul class="nav flex-column mt-5 pt-4">
    <!-- Gestion utilisateurs -->
    <li class="nav-item mb-2">

        <button type="button" class="btn sidebar-btn w-100 text-start nav-link" data-bs-toggle="collapse"
            data-bs-target="#productsMenu" aria-expanded="true" aria-controls="productsMenu">

            <i class="fa-solid fa-users me-2"></i>
            Gestion des utilisateurs
        </button>

        <div class="collapse show ps-3 <?= $productsOpen ? 'show' : '' ?>" id="productsMenu">
            <a class="nav-link sidebar-link <?= isActive('/gestion-utilisateurs') ? 'active-link' : '' ?>"
                href="<?= BASE_URL . '/gestion-utilisateurs' ?>">
                Administration utilisateurs
            </a>
        </div>

    </li>
</ul>
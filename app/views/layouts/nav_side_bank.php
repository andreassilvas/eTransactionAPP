<?php
function isActive($path)
{
    return strpos($_SERVER['REQUEST_URI'], $path) !== false;
}
?>

<ul class="nav flex-column mt-5 pt-4">

    <!-- Relevé Bancaire -->
    <li class="nav-item mb-2">
        <a class="nav-link sidebar-link <?= isActive('/banque') ? 'active-link' : '' ?>"
            href="<?= BASE_URL . '/banque' ?>">
            <i class="fa-solid fa-file-invoice-dollar me-2"></i>
            Relevé Bancaire
        </a>
    </li>

    <!-- Activité du compte -->
    <li class="nav-item mb-2">
        <a class="nav-link sidebar-link <?= isActive('/commandes') ? 'active-link' : '' ?>"
            href="<?= BASE_URL . '/commandes' ?>">

            <i class="fa-solid fa-list me-2"></i>
            Activité du compte
        </a>
    </li>
    <li class="nav-item mb-2">
        <a class="nav-link sidebar-link <?= isActive('/transferts') ? 'active-link' : '' ?>"
            href="<?= BASE_URL . '/transferts' ?>">

            <i class="fa-solid fa-money-bill-transfer me-2"></i>
            Transfert de fonds
        </a>
    </li>
</ul>
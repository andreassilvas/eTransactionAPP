<?php
// Définir les valeurs par défaut si elles ne sont pas fournies
$releveUrl = $releveUrl ?? '#scrollspyHeading1';
$ordersUrl = $ordersUrl ?? '#scrollspyHeading2';
$logoutUrl = $logoutUrl ?? BASE_URL . '/logout.php';
?>

<nav id="navbar-inventory" class="navbar bg-body-tertiary px-5 mb-3 fixed-top custom-navbar-inventaire">
    <a class="navbar-brand" href="#"></a>
    <ul class="nav nav-pills">
        <li class="nav-item">
            <a class="nav-link d-flex align-items-center gap-3 <?= $activeStep == 1 ? 'active-step-c' : 'inactive' ?>"
                href="<?= BASE_URL . '/tableau-de-bord' ?>">
                <span
                    class="badge rounded-circle d-flex align-items-center justify-content-center <?= $activeStep == 1 ? 'active-step-c' : 'text-bg-secondary' ?>"
                    style="width: 32px; height: 32px;">
                    <i class="fa-solid fa-table fa-lg"></i>
                </span>
                Tableau de Bord
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link d-flex align-items-center gap-3 <?= $activeStep == 2 ? 'active-step-c' : 'inactive' ?>"
                href="<?= BASE_URL . '/gestion-des-produits' ?>">
                <span
                    class="badge rounded-circle d-flex align-items-center justify-content-center <?= $activeStep == 2 ? 'active-step-c' : 'text-bg-secondary' ?>"
                    style="width: 32px; height: 32px;">
                    <i class="fa-solid fa-list-check"></i>
                </span>
                Gestion des Produits
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link d-flex align-items-center gap-3 <?= $activeStep == 3 ? 'active-step-c' : 'inactive' ?>"
                href="<?= BASE_URL ?>/gestion-utilisateurs">
                <span
                    class="badge rounded-circle d-flex align-items-center justify-content-center <?= $activeStep == 3 ? 'active-step-c' : 'text-bg-secondary' ?>"
                    style="width: 32px; height: 32px;">
                    <i class="fa-solid fa-users"></i>
                </span>
                Gestion des Utilisateurs
            </a>
        </li>
        <a class="nav-link d-flex align-items-center gap-3" href="<?= $logoutUrl ?>">
            <i class="fa-solid fa-right-from-bracket fa-lg" style="color: #E0B0FF;"></i>
        </a>
    </ul>
</nav>
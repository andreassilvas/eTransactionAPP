<?php
// Set defaults if not provided - Re visit for more...
$releveUrl = $releveUrl ?? '#scrollspyHeading1';
$ordersUrl = $ordersUrl ?? '#scrollspyHeading2';
$logoutUrl = $logoutUrl ?? BASE_URL . '/logout.php';
?>

<nav id="navbar-example2" class="navbar bg-body-tertiary px-5 mb-3 fixed-top custom-navbar-releve">
    <a class="navbar-brand" href="#"></a>
    <ul class="nav nav-pills">
        <li class="nav-item">
            <a class="nav-link d-flex align-items-center gap-3 <?= $activeStep == 1 ? 'active-step-b' : 'inactive' ?>"
                href="<?= BASE_URL . '/releve' ?>">
                <span
                    class="badge rounded-circle d-flex align-items-center justify-content-center <?= $activeStep == 1 ? 'active-step-b' : 'text-bg-secondary' ?>"
                    style="width: 32px; height: 32px;">
                    <i class="fa-solid fa-building-columns fa-lg"></i>
                </span>
                Client relevé bancaire
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link d-flex align-items-center gap-3 <?= $activeStep == 2 ? 'active-step-b' : 'inactive' ?>"
                href="<?= BASE_URL . '/commandes' ?>">
                <span
                    class="badge rounded-circle d-flex align-items-center justify-content-center <?= $activeStep == 2 ? 'active-step-b' : 'text-bg-secondary' ?>"
                    style="width: 32px; height: 32px;">
                    <i class="fa-solid fa-list"></i>
                </span>
                Client relevés de commandes
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link d-flex align-items-center gap-3 <?= $activeStep == 3 ? 'active-step-b' : 'inactive' ?>"
                href="<?= BASE_URL . '/produits' ?>">
                <span
                    class="badge rounded-circle d-flex align-items-center justify-content-center <?= $activeStep == 3 ? 'active-step-b' : 'text-bg-secondary' ?>"
                    style="width: 32px; height: 32px;">
                    <i class="fa-solid fa-boxes"></i>
                </span>
                Produits en stock
            </a>
        </li>
        <a class="nav-link d-flex align-items-center gap-3" href="<?= $logoutUrl ?>">
            <i class="fa-solid fa-right-from-bracket fa-lg" style="color: #575757;"></i>
        </a>

    </ul>
</nav>
<?php
// Set defaults if not provided - Re visit for more...
$releveUrl = $releveUrl ?? '#scrollspyHeading1';
$ordersUrl = $ordersUrl ?? '#scrollspyHeading2';
$logoutUrl = $logoutUrl ?? BASE_URL . '/logout.php';
?>

<nav id="navbar" class="navbar bg-body-tertiary px-5 mb-3 fixed-top">
    <a class="navbar-brand" href="#"></a>
    <ul class="nav nav-pills">
        <li class="nav-item">
            <a class="nav-link disabled d-flex align-items-center gap-3 <?= $activeStep == 1 ? 'active-step-a' : 'inactive' ?>"
                href="#scrollspyHeading1">
                <span
                    class="badge rounded-circle d-flex align-items-center justify-content-center <?= $activeStep == 1 ? 'active-step-a' : 'text-bg-secondary' ?>"
                    style="width: 27px; height: 27px;">
                    1
                </span>
                Expédition
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link disabled d-flex align-items-center gap-3 <?= $activeStep == 2 ? 'active-step-a' : 'inactive' ?>"
                href="#scrollspyHeading1">
                <span
                    class="badge rounded-circle d-flex align-items-center justify-content-center <?= $activeStep == 2 ? 'active-step-a' : 'text-bg-secondary' ?>"
                    style="width: 27px; height: 27px;">
                    2
                </span>
                Paiement
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link disabled d-flex align-items-center gap-3 <?= $activeStep == 3 ? 'active-step-a' : 'inactive' ?>"
                href="#scrollspyHeading1">
                <span
                    class="badge rounded-circle d-flex align-items-center justify-content-center <?= $activeStep == 3 ? 'active-step-a' : 'text-bg-secondary' ?>"
                    style=" width: 27px; height: 27px;">
                    3
                </span>
                Vérification
            </a>
        </li>
        <a class="nav-link d-flex align-items-center gap-3" href="<?= $logoutUrl ?>">
            <i class="fa-solid fa-right-from-bracket fa-lg" style="color: #575757;"></i>
        </a>

    </ul>
</nav>
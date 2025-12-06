<?php
$logoutUrl = $logoutUrl ?? BASE_URL . '/logout.php';
?>

<nav id="navbar-inventory" class="navbar bg-body-tertiary px-5 mb-3 fixed-top custom-navbar-inventaire">
    <ul class="nav nav-pills py-2 w-100 d-flex justify-content-end">
        <li class="nav-item">
            <a class="nav-link d-flex align-items-center" href="<?= $logoutUrl ?>">
                <i class="fa-solid fa-house fa-lg" style="color: #E0B0FF;"></i>
            </a>
        </li>
    </ul>
</nav>
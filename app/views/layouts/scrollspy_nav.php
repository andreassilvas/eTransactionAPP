<nav id="navbar-example2" class="navbar bg-body-tertiary px-5 mb-3">
    <a class="navbar-brand" href="#"></a>
    <ul class="nav nav-pills">
        <li class="nav-item">
            <a class="nav-link d-flex align-items-center gap-3 <?= $activeStep == 1 ? 'active-step' : 'inactive' ?>"
                href="#scrollspyHeading1">
                <span
                    class="badge rounded-circle d-flex align-items-center justify-content-center <?= $activeStep == 1 ? 'active-step' : 'text-bg-secondary' ?>"
                    style="width: 27px; height: 27px;">
                    1
                </span>
                Expédition
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link d-flex align-items-center gap-3 <?= $activeStep == 2 ? 'active-step' : 'inactive' ?>"
                href="#scrollspyHeading1">
                <span
                    class="badge rounded-circle d-flex align-items-center justify-content-center <?= $activeStep == 2 ? 'active-step' : 'text-bg-secondary' ?>"
                    style="width: 27px; height: 27px;">
                    2
                </span>
                Paiement
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link d-flex align-items-center gap-3 <?= $activeStep == 3 ? 'active-step' : 'inactive' ?>"
                href="#scrollspyHeading1">
                <span
                    class="badge rounded-circle d-flex align-items-center justify-content-center <?= $activeStep == 3 ? 'active-step' : 'text-bg-secondary' ?>"
                    style=" width: 27px; height: 27px;">
                    3
                </span>
                Verification
            </a>
        </li>
        <a class="nav-link d-flex align-items-center gap-3" href="/eTransactionAPP/public/logout.php">
            <i class="fa-solid fa-right-from-bracket fa-lg" style="color: #575757;"></i>
        </a>

    </ul>
</nav>
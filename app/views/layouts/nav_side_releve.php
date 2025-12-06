<ul class="nav flex-column py-5">
    <li class="nav-item">
        <?php
        $dashboardUrl = BASE_URL . '/releve';
        $currentPath = $_SERVER['REQUEST_URI'];
        $isDisabled = strpos($currentPath, '/releve') !== false ? 'disabled' : '';
        ?>
        <a class="nav-link <?= $isDisabled ?>" aria-current="page" href="<?= $dashboardUrl ?>">Relevé Bancaire
            Bancaire</a>
    </li>
    <li class="nav-item">
        <?php
        $dashboardUrl = BASE_URL . '/commandes';
        $currentPath = $_SERVER['REQUEST_URI'];
        $isDisabled = strpos($currentPath, '/commandes') !== false ? 'disabled' : '';
        ?>
        <a class="nav-link <?= $isDisabled ?>" aria-current="page" href="<?= $dashboardUrl ?>">Historique des
            commandes</a>
    </li>
</ul>
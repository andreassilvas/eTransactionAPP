<ul class="nav flex-column py-5">
    <li class="nav-item">
        <?php
        $dashboardUrl = BASE_URL . '/tableau-de-bord';
        $currentPath = $_SERVER['REQUEST_URI'];
        $isDisabled = strpos($currentPath, '/tableau-de-bord') !== false ? 'disabled' : '';
        ?>
        <a class="nav-link <?= $isDisabled ?>" aria-current="page" href="<?= $dashboardUrl ?>">Tableau de Bord</a>
    </li>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Gestion des Produits
        </a>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="<?= BASE_URL . '/produits-en-stock' ?>">Produits en stock</a></li>
            <li><a class="dropdown-item" href="<?= BASE_URL . '/produits-livre' ?>">Produits expédiés</a></li>
            <li><a class="dropdown-item" href="<?= BASE_URL . '/administration-des-produits' ?>">Admin produits</a></li>
        </ul>
    </li>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Gestion des Utilisateurs
        </a>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="<?= BASE_URL ?>/gestion-utilisateurs">Admin utilisateurs</a></li>
            <li><a class="dropdown-item" href="#">Comptes bancaires</a></li>
        </ul>
    </li>
</ul>
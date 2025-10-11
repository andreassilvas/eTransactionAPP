<?php
require_once __DIR__ . '/../init.php';

/**
 * Script de contrôle d'accès pour les pages privées.
 *
 * - Empêche la mise en cache afin que le contenu sensible
 *   ne soit pas visible lors des navigations arrière/avant.
 * - Vérifie si l'utilisateur est connecté avant d'accéder à la page.
 */

// Empêcher la mise en cache pour le contenu privé
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: 0");

// Vérification d'authentification : rediriger si l'utilisateur n'est pas connecté
if (!isset($_SESSION['client_id'])) {
    header("Location: " . BASE_URL);
    exit;
}

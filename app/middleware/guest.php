<?php
require_once __DIR__ . '/../init.php';

/**
 * Script de redirection pour les utilisateurs déjà connectés.
 *
 * - Si un utilisateur est connecté, il est automatiquement
 *   redirigé vers la page d'expédition.
 */

// Vérifie si l'utilisateur est connecté
if (isset($_SESSION['client_id'])) {
    // Redirige vers la page d'expédition
    header("Location: /eTransactionAPP/public/expedition");
    exit;
}

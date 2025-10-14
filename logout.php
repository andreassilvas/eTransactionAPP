<?php
require_once __DIR__ . '/app/init.php';
session_unset();
session_destroy();

// Empêcher la mise en cache
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

// Rediriger vers la page de connexion
header("Location: " . BASE_URL . "/");
exit;

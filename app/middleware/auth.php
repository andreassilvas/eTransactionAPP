<?php
require_once __DIR__ . '/../init.php';

// Prevent caching (so back/forward won't show private content)
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: 0");

// Auth check
if (!isset($_SESSION['client_id'])) {
    header("Location: /eTransactionAPP/public/");
    exit;
}

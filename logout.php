<?php
require_once __DIR__ . '/app/init.php';
session_unset();
session_destroy();

// Prevent caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

// Redirect to login page
header("Location: " . BASE_URL . "/");
exit;

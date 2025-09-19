<?php
require_once __DIR__ . '/../config/database.php';

try {
    $pdo = Database::getConnection();
    echo "Database connection successful!";
} catch (Exception $e) {
    echo "Database connection failed: " . $e->getMessage();
}

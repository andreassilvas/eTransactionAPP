<?php
function authMiddleware($role = null)
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['user_id'])) {
        header("Location: " . BASE_URL);
        exit;
    }

    if ($role && $_SESSION['role'] !== $role) {
        header("Location: " . BASE_URL);
        exit;
    }
}


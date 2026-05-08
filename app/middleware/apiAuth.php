<?php

use App\Models\UserToken;

function apiAuth()
{
    header('Content-Type: application/json');

    // Get headers
    $headers = getallheaders();

    // Try custom header first
    $token = $headers['X-Auth-Token'] ?? $headers['x-auth-token'] ?? null;

    // Fallback to httpOnly cookie
    if (!$token && isset($_COOKIE['auth_token'])) {
        $token = $_COOKIE['auth_token'];
    }

    // No token found
    if (!$token) {
        http_response_code(401);
        echo json_encode([
            'status' => 'error',
            'message' => 'Unauthorized access: No token provided'
        ]);
        exit;
    }

    // Validate token in DB
    $tokenModel = new UserToken();
    $tokenData = $tokenModel->findValidToken($token);

    if (!$tokenData) {
        http_response_code(401);
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid or expired token'
        ]);
        exit;
    }

    // Inject user into request
    $_REQUEST['user'] = [
        'id' => $tokenData['client_id']
    ];
}







<?php
namespace App\Models;

class UserToken
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function create($clientId, $token, $expiresAt)
    {
        $stmt = $this->db->prepare("
            INSERT INTO user_tokens (client_id, token, expires_at)
            VALUES (:client_id, :token, :expires_at)
        ");

        return $stmt->execute([
            'client_id' => $clientId,
            'token' => $token,
            'expires_at' => $expiresAt
        ]);
    }

    public function findValidToken($token)
    {
        $stmt = $this->db->prepare("
            SELECT * FROM user_tokens
            WHERE token = :token
            AND expires_at > NOW()
            LIMIT 1
        ");

        $stmt->execute(['token' => $token]);
        return $stmt->fetch();
    }
}
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
        LIMIT 1
    ");

        $stmt->execute([
            'token' => $token
        ]);

        $tokenData = $stmt->fetch();

        // Token not found
        if (!$tokenData) {
            return false;
        }

        // Token expired
        if (strtotime($tokenData['expires_at']) < time()) {
            return false;
        }

        return $tokenData;
    }
    //   public function findValidToken($token)
    // {
    //     $stmt = $this->db->prepare("
    //         SELECT * FROM user_tokens
    //         WHERE token = :token
    //         AND expires_at > NOW()
    //         LIMIT 1
    //     ");

    //     $stmt->execute(['token' => $token]);
    //     // echo json_encode([
    //     //     'mysql_now' => $this->db->query("SELECT NOW()")->fetchColumn(),
    //     //     'token_expires' => $stmt->fetch()['expires_at'] ?? null
    //     // ]);
    //     // exit;
    //     return $stmt->fetch();

    // }
    public function deleteByToken($token)
    {
        $stmt = $this->db->prepare("DELETE FROM user_tokens WHERE token = :token");
        $stmt->execute(['token' => $token]);
    }

    public function deleteByClientId($clientId)
    {
        $stmt = $this->db->prepare("DELETE FROM user_tokens WHERE client_id = :client_id");
        $stmt->execute(['client_id' => $clientId]);
    }
}


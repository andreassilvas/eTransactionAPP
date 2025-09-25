<?php
namespace App\Models;
use PDO;

class Payment extends Model
{
    protected $db;
    protected $table = 'payments';

    public function create($data)
    {
        // Generate simulated transaction ID
        $transactionId = 'SIM' . strtoupper(uniqid());

        $sql = "INSERT INTO {$this->table} 
                (expedition_id, amount, status, method, last4, transaction_id, created_at) 
                VALUES (:expedition_id, :amount, :status, :method, :last4, :transaction_id, NOW())";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':expedition_id' => $data['expedition_id'],
            ':amount' => $data['amount'],
            ':status' => $data['status'], // pending initially
            ':method' => $data['method'] ?? 'Visa', // simulate card type
            ':last4' => $data['last4'] ?? '1234',   // simulate last 4 digits
            ':transaction_id' => $transactionId
        ]);

        return $this->db->lastInsertId();
    }

    public function updateStatus($id, $status)
    {
        $sql = "UPDATE {$this->table} SET status = :status, updated_at = NOW() WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':status' => $status, ':id' => $id]);
    }

    public function findByExpedition($expeditionId)
    {
        $sql = "SELECT * FROM {$this->table} WHERE expedition_id = :expedition_id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':expedition_id' => $expeditionId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findById($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

}

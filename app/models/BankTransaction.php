<?php
namespace App\Models;

use PDO;

class BankTransaction
{
    protected $db;
    protected $table = 'bank_transactions';

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    //Get all transactions for a login client
    public function getByClientId($clientId)
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE client_id = :client_id 
                ORDER BY transaction_date DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':client_id', $clientId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //Get the latest balance for a login client
    public function getBalance($clientId)
    {
        $sql = "SELECT balance 
                FROM {$this->table} 
                WHERE client_id = :client_id 
                ORDER BY transaction_date DESC 
                LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':client_id' => $clientId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ? floatval($row['balance']) : 0.00;
    }

    //Create a new transaction (debit or credit)
    public function create(array $data)
    {
        $sql = "INSERT INTO {$this->table} 
                (client_id, description, credit, debit, balance, transaction_date) 
                VALUES (:client_id, :description, :credit, :debit, :balance, NOW())";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':client_id' => $data['client_id'],
            ':description' => $data['description'] ?? '',
            ':credit' => $data['credit'] ?? 0,
            ':debit' => $data['debit'] ?? 0,
            ':balance' => $data['balance'] ?? 0
        ]);

        return $this->db->lastInsertId();
    }
    public function addTransaction($clientId, $description, $credit, $debit, $balance)
    {
        return $this->create([
            'client_id' => $clientId,
            'description' => $description,
            'credit' => $credit,
            'debit' => $debit,
            'balance' => $balance
        ]);
    }
}

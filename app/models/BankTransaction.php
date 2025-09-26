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

    public function getByClientId($clientId)
    {
        $sql = "SELECT * FROM {$this->table} WHERE client_id = :client_id ORDER BY transaction_date DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':client_id', $clientId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Returns array, empty if no records
    }
}

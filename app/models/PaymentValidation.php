<?php
namespace App\Models;
use PDO;

class PaymentValidation extends Model
{
    protected $table = 'payment_validation';

    public function findValidCard($clientId, $cardNumber, $expiryMonth, $expiryYear, $cvv)
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE client_id = :client_id
                  AND card_number = :card_number
                  AND expiry_month = :expiry_month
                  AND expiry_year = :expiry_year
                  AND cvv = :cvv
                LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':client_id' => $clientId,
            ':card_number' => $cardNumber,
            ':expiry_month' => $expiryMonth,
            ':expiry_year' => $expiryYear,
            ':cvv' => $cvv
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

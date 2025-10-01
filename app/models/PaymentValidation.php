<?php
namespace App\Models;
use PDO;

class PaymentValidation extends Model
{
    protected $table = 'payment_validation';

    public function findValidCard($clientId, $cardName, $cardNumber, $codePostal, $expiryDate, $cvv)
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE client_id = :client_id
                  AND card_name = :card_name
                  AND card_number = :card_number
                  AND code_postal = :code_postal
                  AND expiry_date = :expiry_date
                  AND cvv = :cvv
                LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':client_id' => $clientId,
            ':card_name' => $cardName,
            ':card_number' => $cardNumber,
            ':code_postal' => $codePostal,
            ':expiry_date' => $expiryDate,
            ':cvv' => $cvv
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

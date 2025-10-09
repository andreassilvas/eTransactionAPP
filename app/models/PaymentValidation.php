<?php
namespace App\Models;
use PDO;

/**
 * Class PaymentValidation
 *
 * Modèle responsable de la validation des informations de carte de crédit.
 * Permet de vérifier si une carte correspond aux informations enregistrées pour un client donné.
 *
 * @package App\Models
 */
class PaymentValidation extends Model
{
    /**
     * @var string Nom de la table de validation des paiements
     */
    protected $table = 'payment_validation';

    /**
     * Vérifie si une carte de crédit est valide pour un client donné.
     *
     * @param int $clientId Identifiant du client
     * @param string $cardName Nom du titulaire de la carte
     * @param string $cardNumber Numéro complet de la carte
     * @param string $codePostal Code postal associé à la carte
     * @param string $expiryDate Date d'expiration (format MM/AA)
     * @param string $cvv Code de sécurité
     * @return array|false Tableau associatif avec les informations de la carte ou false si non trouvé
     */
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

        // Retourne les informations de la carte si valide, sinon false
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

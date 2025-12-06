<?php
namespace App\Models;

use PDO;

/**
 * Class BankTransaction
 *
 * Modèle responsable de la gestion des transactions bancaires des clients.
 * Permet de récupérer les transactions, obtenir le solde actuel et créer de nouvelles transactions.
 *
 * @package App\Models
 */

class BankTransaction
{
    /**
     * @var PDO Connexion à la base de données
     */
    protected $db;

    /**
     * @var string Nom de la table des transactions bancaires
     */
    protected $table = 'bank_transactions';

    /**
     * BankTransaction constructor.
     *
     * @param PDO $db Connexion PDO à la base de données
     */
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Récupère toutes les transactions pour un client donné.
     *
     * Les transactions sont triées par date décroissante.
     *
     * @param int $clientId Identifiant du client
     * @return array Tableau associatif des transactions
     */
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

    /**
     * Crée une nouvelle transaction bancaire (crédit ou débit).
     *
     * @param array $data Données de la transaction :
     *                    - client_id : int
     *                    - description : string
     *                    - credit : float
     *                    - debit : float
     *                    - balance : float
     * @return string ID de la transaction insérée
     */
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
    /**
     * Ajoute une transaction bancaire simplifiée.
     *
     * Appelle la méthode `create` en passant les paramètres directement.
     *
     * @param int $clientId Identifiant du client
     * @param string $description Description de la transaction
     * @param float $credit Montant crédité
     * @param float $debit Montant débité
     * @param float $balance Nouveau solde
     * @return string ID de la transaction insérée
     */
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

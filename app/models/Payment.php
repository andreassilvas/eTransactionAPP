<?php
namespace App\Models;
use PDO;

/**
 * Class Payment
 *
 * Modèle responsable de la gestion des paiements.
 * Permet de créer des paiements, mettre à jour leur statut
 * et récupérer les informations d'un paiement par ID ou par expédition.
 *
 * @package App\Models
 */
class Payment extends Model
{
    protected $db;

    /**
     * @var string Nom de la table des paiements
     */
    protected $table = 'payments';

    /**
     * Crée un nouveau paiement.
     *
     * Génère un ID de transaction simulé et insère les informations du paiement.
     *
     * @param array $data Données du paiement : expedition_id, client_id, amount, status, method, last4
     * @return string ID du paiement créé
     */
    public function create($data)
    {
        // Generate simulated transaction ID
        $transactionId = 'SIM' . strtoupper(uniqid());

        $sql = "INSERT INTO {$this->table} 
                (expedition_id, client_id, amount,`status`, method, last4, transaction_id, created_at) 
                VALUES (:expedition_id, :client_id, :amount, :status, :method, :last4, :transaction_id, NOW())";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':expedition_id' => $data['expedition_id'],
            ':client_id' => $data['client_id'],
            ':amount' => $data['amount'],
            ':status' => $data['status'], // pending initially
            ':method' => $data['method'] ?? 'Visa',
            ':last4' => $data['last4'] ?? '1234',
            ':transaction_id' => $transactionId
        ]);

        return $this->db->lastInsertId();
    }

    /**
     * Met à jour le statut d'un paiement.
     *
     * @param int $id Identifiant du paiement
     * @param string $status Nouveau statut
     * @return bool Succès de l'exécution
     */
    public function updateStatus($id, $status)
    {
        $sql = "UPDATE {$this->table} SET status = :status, updated_at = NOW() WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':status' => $status, ':id' => $id]);
    }

    /**
     * Récupère un paiement par l'ID de l'expédition.
     *
     * @param int $expeditionId Identifiant de l'expédition
     * @return array|false Paiement sous forme de tableau associatif ou false si non trouvé
     */
    public function findByExpedition($expeditionId)
    {
        $sql = "SELECT * FROM {$this->table} WHERE expedition_id = :expedition_id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':expedition_id' => $expeditionId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère un paiement par son ID.
     *
     * @param int $id Identifiant du paiement
     * @return array|false Paiement sous forme de tableau associatif ou false si non trouvé
     */
    public function findById($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

}

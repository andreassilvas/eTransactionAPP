<?php
namespace App\Models;

/**
 * Class ExpeditionItem
 *
 * Modèle responsable de la gestion des articles associés à une expédition.
 * Permet de créer de nouveaux articles pour une expédition et de récupérer
 * tous les articles d'une expédition donnée.
 *
 * @package App\Models
 */
class ExpeditionItem extends Model
{
    /**
     * @var string Nom de la table des articles d'expédition
     */
    protected $table = 'expedition_items';

    /**
     * Crée un nouvel article pour une expédition.
     *
     * @param array $data Données de l'article : expedition_id, product_id, quantity, unit_price
     * @return bool Succès de l'exécution
     */
    public function create($data)
    {
        $sql = "INSERT INTO $this->table (expedition_id, product_id, quantity, unit_price)
                VALUES (:expedition_id, :product_id, :quantity, :unit_price)";
        $sqlcreate = $this->db->prepare($sql);

        // Exécute la requête avec les données fournies
        return $sqlcreate->execute($data);
    }

    /**
     * Récupère tous les articles pour une expédition donnée.
     *
     * Inclut le nom du produit pour chaque article.
     *
     * @param int $expeditionId Identifiant de l'expédition
     * @return array Tableau associatif des articles
     */
    public function getItemsByExpeditionId(int $expeditionId): array
    {
        $sql = "SELECT ei.*, p.name AS product_name
                FROM $this->table ei
                INNER JOIN products p ON ei.product_id = p.id
                WHERE ei.expedition_id = :expedition_id";
        $sqlexpedition = $this->db->prepare($sql);
        $sqlexpedition->bindParam(':expedition_id', $expeditionId, \PDO::PARAM_INT);
        $sqlexpedition->execute();
        return $sqlexpedition->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getTotalExpeditionQuantity(): int
    {
        $sql = "
        SELECT SUM(ei.quantity) AS total_quantity
        FROM expedition_items ei
        INNER JOIN expeditions e ON e.id = ei.expedition_id
        WHERE e.status IN ('pending', 'shipped', 'delivered')
        ";
        $sqlshipped = $this->db->prepare($sql);
        $sqlshipped->execute();
        $result = $sqlshipped->fetch(\PDO::FETCH_ASSOC);
        return (int) ($result['total_quantity'] ?? 0);
    }

    public function getTopProductsByStatus(string $status, int $limit = 5): array
    {
        $status_stage = ['delivered', 'shipped', 'pending'];
        if (!in_array($status, $status_stage))
            return [];

        $sql = "
        SELECT p.name AS product_name,
               SUM(ei.quantity) AS total_quantity
        FROM {$this->table} ei
        INNER JOIN expeditions e ON e.id = ei.expedition_id
        INNER JOIN products p ON p.id = ei.product_id
        WHERE e.status = :status
        GROUP BY ei.product_id
        ORDER BY total_quantity DESC
        LIMIT :limit
    ";
        $sqlstatus = $this->db->prepare($sql);
        $sqlstatus->bindValue(':status', $status);
        $sqlstatus->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $sqlstatus->execute();

        return $sqlstatus->fetchAll(\PDO::FETCH_ASSOC);
    }
}

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
        $stmt = $this->db->prepare($sql);

        // Exécute la requête avec les données fournies
        return $stmt->execute($data);
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
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':expedition_id', $expeditionId, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}

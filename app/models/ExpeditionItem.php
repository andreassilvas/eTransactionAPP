<?php
namespace App\Models;

class ExpeditionItem extends Model
{
    protected $table = 'expedition_items';

    public function create($data)
    {
        $sql = "INSERT INTO $this->table (expedition_id, product_id, quantity, unit_price)
                VALUES (:expedition_id, :product_id, :quantity, :unit_price)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

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

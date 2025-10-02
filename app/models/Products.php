<?php
namespace App\Models;

use PDO;

class Products extends Model
{
    protected $table = 'products';

    // Get all products
    public function all()
    {
        $sql = "SELECT * FROM $this->table ORDER BY name ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id)
    {
        $sql = "SELECT * FROM $this->table WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $sql = "INSERT INTO $this->table (name, category, brand, model, specs, price, stock, warranty_period, support_level, supplier)
                VALUES (:name, :category, :brand, :model, :specs, :price, :stock, :warranty_period, :support_level, :supplier)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($data);
        return $this->db->lastInsertId();
    }
}

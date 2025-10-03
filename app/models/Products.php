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

    /**
     * Find a product by ID
     *
     * @param int $id
     * @return array|false
     */
    public function find($id)
    {
        $sql = "SELECT * FROM $this->table WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
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
    public function update($id, $data)
    {
        $fields = [];
        foreach ($data as $key => $value) {
            $fields[] = "$key = :$key";
        }
        $sql = "UPDATE $this->table SET " . implode(", ", $fields) . " WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $data['id'] = $id;
        return $stmt->execute($data);
    }


}

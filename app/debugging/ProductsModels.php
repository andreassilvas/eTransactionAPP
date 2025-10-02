<?php
namespace App\Models;

use PDO;

class Products extends Model
{
    protected $table = 'products';

    public function all()
    {
        try {
            $sql = "SELECT * FROM $this->table ORDER BY name ASC";
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            die("Query failed: " . $e->getMessage());
        }
    }
}

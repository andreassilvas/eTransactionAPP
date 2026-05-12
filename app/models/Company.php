<?php
namespace App\Models;

use PDO;

class Company extends Model
{
    protected $company = 'companies';
    public function all(): array
    {
        $sql = "SELECT id, name, email
                FROM {$this->company}
                ORDER BY id DESC";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById(int $id)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->company} WHERE id = :id LIMIT 1");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
<?php
namespace App\Models;

class Province extends Model
{
    protected $table = 'provinces';

    /** Liste des provinces actives */
    public function allActive(): array
    {
        $sql = "SELECT id, code, name FROM {$this->table} WHERE is_active = 1 ORDER BY name";
        return $this->db->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
    }

    /** Trouver l’ID par code (QC, ON, …) */
    public function findIdByCode(string $code): ?int
    {
        $sql = "SELECT id FROM {$this->table} WHERE code = :code AND is_active = 1";
        $st = $this->db->prepare($sql);
        $st->execute([':code' => strtoupper($code)]);
        $id = $st->fetchColumn();
        return $id ? (int) $id : null;
    }

    /** CRUD minimal (facultatif) */
    public function create(array $data): bool
    {
        $sql = "INSERT INTO {$this->table} (code, name, is_active) VALUES (:code, :name, :is_active)";
        $st = $this->db->prepare($sql);
        return $st->execute([
            ':code' => strtoupper($data['code']),
            ':name' => $data['name'],
            ':is_active' => $data['is_active'] ?? 1,
        ]);
    }

    public function updateById(int $id, array $data): bool
    {
        $sql = "UPDATE {$this->table} SET code = :code, name = :name, is_active = :is_active WHERE id = :id";
        $st = $this->db->prepare($sql);
        return $st->execute([
            ':code' => strtoupper($data['code']),
            ':name' => $data['name'],
            ':is_active' => $data['is_active'] ?? 1,
            ':id' => $id,
        ]);
    }
}

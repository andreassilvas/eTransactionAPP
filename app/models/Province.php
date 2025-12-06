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

}

<?php
namespace App\Models;

class City extends Model
{
    protected $table = 'cities';

    /** Villes par code de province, avec recherche/pagination */
    public function byProvinceCode(string $code, string $search = '', int $limit = 50, int $offset = 0): array
    {
        $sql = "SELECT c.id, c.name, p.code AS province_code
                FROM {$this->table} c
                JOIN provinces p ON p.id = c.province_id
                WHERE p.code = :code AND c.is_active = 1 AND p.is_active = 1";
        $params = [':code' => strtoupper($code)];

        if ($search !== '') {
            $sql .= " AND c.name LIKE :q";
            $params[':q'] = $search . '%'; // utilisez '%'.$search.'%' si nécessaire
        }

        $count = "SELECT COUNT(*)
                  FROM {$this->table} c
                  JOIN provinces p ON p.id = c.province_id
                  WHERE p.code = :code AND c.is_active = 1 AND p.is_active = 1"
            . ($search !== '' ? " AND c.name LIKE :q" : "");

        // total
        $cst = $this->db->prepare($count);
        foreach ($params as $k => $v)
            $cst->bindValue($k, $v);
        $cst->execute();
        $total = (int) $cst->fetchColumn();

        // items
        $sql .= " ORDER BY c.name LIMIT :lim OFFSET :off";
        $st = $this->db->prepare($sql);
        foreach ($params as $k => $v)
            $st->bindValue($k, $v);
        $st->bindValue(':lim', $limit, \PDO::PARAM_INT);
        $st->bindValue(':off', $offset, \PDO::PARAM_INT);
        $st->execute();

        return [
            'items' => $st->fetchAll(\PDO::FETCH_ASSOC),
            'limit' => $limit,
            'offset' => $offset,
            'total' => $total,
        ];
    }

    /** Liste générique (option province + recherche) */
    public function listing(?string $provinceCode, string $search = '', int $limit = 50, int $offset = 0): array
    {
        $sql = "SELECT c.id, c.name, p.code AS province_code
                FROM {$this->table} c
                JOIN provinces p ON p.id = c.province_id
                WHERE c.is_active = 1 AND p.is_active = 1";
        $params = [];

        if ($provinceCode) {
            $sql .= " AND p.code = :code";
            $params[':code'] = strtoupper($provinceCode);
        }
        if ($search !== '') {
            $sql .= " AND c.name LIKE :q";
            $params[':q'] = $search . '%';
        }

        $count = "SELECT COUNT(*)
                  FROM {$this->table} c
                  JOIN provinces p ON p.id = c.province_id
                  WHERE c.is_active = 1 AND p.is_active = 1"
            . ($provinceCode ? " AND p.code = :code" : "")
            . ($search !== '' ? " AND c.name LIKE :q" : "");

        $sql .= " ORDER BY c.name LIMIT :lim OFFSET :off";

        $st = $this->db->prepare($sql);
        foreach ($params as $k => $v)
            $st->bindValue($k, $v);
        $st->bindValue(':lim', $limit, \PDO::PARAM_INT);
        $st->bindValue(':off', $offset, \PDO::PARAM_INT);
        $st->execute();

        $items = $st->fetchAll(\PDO::FETCH_ASSOC);

        $cst = $this->db->prepare($count);
        foreach ($params as $k => $v)
            $cst->bindValue($k, $v);
        $cst->execute();
        $total = (int) $cst->fetchColumn();

        return ['items' => $items, 'limit' => $limit, 'offset' => $offset, 'total' => $total];
    }

    public function getById(int $id): ?array
    {
        $sql = "SELECT c.id, c.name, p.code AS province_code
                FROM {$this->table} c
                JOIN provinces p ON p.id = c.province_id
                WHERE c.id = :id AND c.is_active = 1";
        $st = $this->db->prepare($sql);
        $st->execute([':id' => $id]);
        $row = $st->fetch(\PDO::FETCH_ASSOC);
        return $row ?: null;
    }
}

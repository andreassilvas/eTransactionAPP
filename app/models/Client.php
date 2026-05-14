<?php
namespace App\Models;

use PDO;

class Client extends Model
{
    protected $table = 'users';

    public function findByEmail(string $email)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE email = :email LIMIT 1");
        $stmt->bindValue(':email', strtolower(trim($email)), PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function existsByEmail(string $email, ?int $excludeId = null): bool
    {
        $sql = "SELECT 1 FROM {$this->table} WHERE email = :email";
        if ($excludeId !== null)
            $sql .= " AND id <> :id";
        $st = $this->db->prepare($sql);
        $st->bindValue(':email', strtolower(trim($email)));
        if ($excludeId !== null)
            $st->bindValue(':id', $excludeId, PDO::PARAM_INT);
        $st->execute();
        return (bool) $st->fetchColumn();
    }

    public function findById(int $id)
    {
        $stmt = $this->db->prepare("
            SELECT
                id,
                company_id,
                name,
                lastname,
                email,
                phone,
                extention,
                address,
                city,
                province,
                postcode,
                role
            FROM {$this->table}
            WHERE id = :id
            LIMIT 1
        ");

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    /* ---------- Listing ---------- */
    public function all(): array
    {
        $sql = "SELECT id, name, lastname, phone, extention, email, address, city, province, postcode, password, role
                FROM {$this->table}
                ORDER BY id DESC";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    /* ---------- Create ---------- */

    public function create(array $data): string
    {
        $clean = $this->normalize($data);

        $sql = "INSERT INTO {$this->table}
                (company_id, name, lastname, phone, extention, email, address, city, province, postcode, password, role)
                VALUES (:company_id, :name, :lastname, :phone, :extention, :email, :address, :city, :province, :postcode, :password, :role)";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':company_id', $clean['company_id']);
        $stmt->bindValue(':name', $clean['name']);
        $stmt->bindValue(':lastname', $clean['lastname']);
        $stmt->bindValue(':phone', $clean['phone']);
        $stmt->bindValue(':extention', $clean['extention']);
        $stmt->bindValue(':email', $clean['email']);
        $stmt->bindValue(':address', $clean['address']);
        $stmt->bindValue(':city', $clean['city']);
        $stmt->bindValue(':province', $clean['province']);
        $stmt->bindValue(':postcode', $clean['postcode']);
        $stmt->bindValue(':password', $clean['password']);
        $stmt->bindValue(':role', $clean['role']);
        $stmt->execute();
        return $this->db->lastInsertId();
    }

    /* ---------- Update (dynamic) ---------- */

    /**
     * Update only provided fields; omit 'password' if empty so it isn't cleared.
     * Also treats empty optional fields as NULL (phone, extention, address, city, province, postcode).
     */
    public function updateById(int $id, array $d): int
    {
        // Normalize inputs (trim, lower email)
        $clean = [];
        foreach (['name', 'lastname', 'phone', 'extention', 'email', 'address', 'city', 'province', 'postcode', 'password', 'role', 'has_card'] as $k) {
            if (array_key_exists($k, $d)) {
                $v = is_string($d[$k]) ? trim($d[$k]) : $d[$k];
                if (in_array($k, ['phone', 'extention', 'address', 'city', 'province', 'postcode'], true)) {
                    $v = ($v === '') ? null : $v; // optional -> NULL when empty
                }
                $clean[$k] = $v;
            }
        }
        if (isset($clean['email']))
            $clean['email'] = strtolower($clean['email']);

        // Build SET only for provided keys; skip empty password
        $sets = [];
        $params = [':id' => $id];
        foreach ($clean as $k => $v) {
            if ($k === 'password' && ($v === '' || $v === null))
                continue;
            $sets[] = "{$k} = :{$k}";
            $params[":{$k}"] = $v;
        }
        if (!$sets)
            return 0; // nothing to update

        $sql = "UPDATE {$this->table} SET " . implode(', ', $sets) . " WHERE id = :id";
        $st = $this->db->prepare($sql);
        $st->execute($params);
        return $st->rowCount(); //how many rows MySQL changed
    }

    public function deleteById(int $id): bool
    {
        $st = $this->db->prepare("DELETE FROM {$this->table} WHERE id = :id");
        return $st->execute([':id' => $id]);
    }

    public function allByCompanyId(int $companyId): array
    {
        $stmt = $this->db->prepare("
        SELECT
            id,
            company_id,
            name,
            lastname,
            phone,
            extention,
            email,
            address,
            city,
            province,
            postcode,
            password,
            role
        FROM {$this->table}
        WHERE company_id = :company_id
        ORDER BY id DESC
    ");

        $stmt->bindValue(
            ':company_id',
            $companyId,
            PDO::PARAM_INT
        );

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* ---------- Helpers ---------- */

    /**
     * Trim strings, lowercase email, convert empty optional fields to NULL.
     * Returns only known keys; ignores unknown keys.
     */
    private function normalize(array $d): array
    {
        $out = [];
        // required fields
        if (isset($d['company_id'])) {
            $out['company_id'] = (int) $d['company_id'];
        }
        foreach ([
            'name',
            'lastname',
            'email',
            'role'
        ] as $k) {
            if (isset($d[$k]))
                $out[$k] = is_string($d[$k]) ? trim($d[$k]) : $d[$k];
        }
        if (isset($out['email']))
            $out['email'] = strtolower($out['email']);

        // optionals: empty string -> null
        foreach (['phone', 'extention', 'address', 'city', 'province', 'postcode'] as $k) {
            if (array_key_exists($k, $d)) {
                $v = is_string($d[$k]) ? trim($d[$k]) : $d[$k];
                $out[$k] = ($v === '' ? null : $v);
            }
        }
        // password
        if (array_key_exists('password', $d)) {
            $v = is_string($d['password']) ? trim($d['password']) : $d['password'];
            $out['password'] = ($v === '' ? '' : $v); // empty string preserved so updateById can skip it
        }

        return $out;
    }
    public function allWithoutCardsByCompanyId($companyId)
    {
        $sql = "
            SELECT
                id,
                name,
                lastname,
                role
            FROM users
            WHERE company_id = :company_id
            AND has_card = 0
            ORDER BY name ASC
        ";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            ':company_id' => $companyId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

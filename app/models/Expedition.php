<?php
class Expedition extends Model
{
    protected $table = 'expeditions';

    /**
     * Create a new expedition record
     */
    public function create($data)
    {
        $sql = "INSERT INTO $this->table 
                (client_id, ship_email,ship_address, ship_city, ship_province, ship_postcode, date, status) 
                VALUES (:client_id, :ship_email,:ship_address, :ship_city, :ship_province, :ship_postcode, :date, :status)";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':client_id' => $data['client_id'],
            ':ship_email' => $data['ship_email'],
            ':ship_address' => $data['ship_address'],
            ':ship_city' => $data['ship_city'],
            ':ship_province' => $data['ship_province'],
            ':ship_postcode' => $data['ship_postcode'],
            ':date' => $data['date'],
            ':status' => $data['status']
        ]);

        return $this->db->lastInsertId();
    }

    /**
     * Get all expeditions for a given client
     */
    public function getByClientId($clientId)
    {
        $sql = "SELECT * FROM $this->table WHERE client_id = :client_id ORDER BY date DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':client_id', $clientId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Find one expedition by ID
     */
    public function findById($id)
    {
        $sql = "SELECT * FROM $this->table WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Update an expedition (e.g., update status)
     */
    public function updateStatus($id, $status)
    {
        $sql = "UPDATE $this->table SET status = :status WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Delete an expedition
     */
    public function delete($id)
    {
        $sql = "DELETE FROM $this->table WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}

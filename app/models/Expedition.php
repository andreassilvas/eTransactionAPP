<?php
namespace App\Models;

use PDO;

class Expedition extends Model
{
    protected $table = 'expeditions';


    //Create a new expedition record
    public function create($data, $status = 'pending')
    {
        $tranckingnum = 'TRACK' . strtoupper(uniqid());
        $sql = "INSERT INTO $this->table 
                (client_id, ship_email,ship_address, ship_city, ship_province, ship_postcode, ship_name, ship_lastname, ship_phone, tracking_number, date, status) 
                VALUES (:client_id, :ship_email,:ship_address, :ship_city, :ship_province, :ship_postcode, :tracking_number,:date, :status)";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':client_id' => $data['client_id'],
            ':ship_email' => $data['ship_email'],
            ':ship_address' => $data['ship_address'],
            ':ship_city' => $data['ship_city'],
            ':ship_province' => $data['ship_province'],
            ':ship_postcode' => $data['ship_postcode'],
            'ship_name' => $data['ship_name'],
            'ship_lastname' => $data['ship_lastname'],
            'ship_phone' => $data['ship_phone'],
            ':tracking_number' => $tranckingnum,
            ':date' => $data['date'],
            ':status' => $status
        ]);

        return $this->db->lastInsertId();
    }

    //Get all expeditions for a given client
    public function getByClientId($clientId)
    {
        $sql = "SELECT * FROM $this->table WHERE client_id = :client_id ORDER BY date DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':client_id', $clientId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //Find one expedition by ID
    public function findById($id)
    {
        $sql = "SELECT * FROM $this->table WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    //Update an expedition (update status)
    public function updateStatus($id, $status)
    {
        $sql = "UPDATE $this->table SET status = :status WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':status', $status, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    //Delete an expedition
    public function delete($id)
    {
        $sql = "DELETE FROM $this->table WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function findWithClientById($expeditionId)
    {
        $sql = "SELECT e.*, c.name, c.lastname, c.phone
                FROM $this->table e
                JOIN clients c ON e.client_id = c.id
                WHERE e.id = :id
                LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $expeditionId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

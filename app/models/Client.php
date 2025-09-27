<?php
namespace App\Models;
use PDO;

class Client extends Model
{
    protected $table = 'clients';

    public function findByEmail($email)
    {
        $stmt = $this->db->prepare("SELECT * FROM $this->table WHERE email = :email LIMIT 1");
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findByEmailOrPhone($email, $phone)
    {
        $stmt = $this->db->prepare("SELECT * FROM $this->table WHERE email = :email OR phone = :phone LIMIT 1");
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt->bindParam(":phone", $phone, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $sql = "INSERT INTO $this->table 
            (name, lastname, phone, extention, email, address, city, province, postcode)
            VALUES (:name, :lastname, :phone, :extention, :email, :address, :city, :province, :postcode)";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':name' => $data['name'],
            ':lastname' => $data['lastname'],
            ':phone' => $data['phone'],
            ':extention' => $data['extention'],
            ':email' => $data['email'],
            ':address' => $data['address'],
            ':city' => $data['city'],
            ':province' => $data['province'],
            ':postcode' => $data['postcode']
        ]);

        return $this->db->lastInsertId();
    }
    public function findById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM $this->table WHERE id = :id LIMIT 1");
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

}

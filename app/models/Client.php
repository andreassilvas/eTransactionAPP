<?php
namespace App\Models;
use PDO;

/**
 * Class Client
 *
 * Modèle responsable de la gestion des clients.
 * Permet de récupérer les informations clients par email, téléphone ou ID,
 * et de créer de nouveaux clients.
 *
 * @package App\Models
 */

class Client extends Model
{
    /**
     * @var string Nom de la table clients
     */
    protected $table = 'clients';

    /**
     * Récupère un client par son email.
     *
     * @param string $email Email du client
     * @return array|false Tableau associatif du client ou false si non trouvé
     */
    public function findByEmail($email)
    {
        $stmt = $this->db->prepare("SELECT * FROM $this->table WHERE email = :email LIMIT 1");
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère un client par son email ou son téléphone.
     *
     * @param string $email Email du client
     * @param string $phone Téléphone du client
     * @return array|false Tableau associatif du client ou false si non trouvé
     */
    public function findByEmailOrPhone($email, $phone)
    {
        $stmt = $this->db->prepare("SELECT * FROM $this->table WHERE email = :email OR phone = :phone LIMIT 1");
        $stmt->bindParam(":email", $email, PDO::PARAM_STR);
        $stmt->bindParam(":phone", $phone, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Crée un nouveau client.
     *
     * @param array $data Données du client : name, lastname, phone, extention, email, address, city, province, postcode
     * @return string ID du client créé
     */
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

    /**
     * Récupère un client par son ID.
     *
     * @param int $id Identifiant du client
     * @return array|false Tableau associatif du client ou false si non trouvé
     */
    public function findById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM $this->table WHERE id = :id LIMIT 1");
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

}

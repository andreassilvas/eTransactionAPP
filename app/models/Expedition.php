<?php
namespace App\Models;

use PDO;

/**
 * Class Expedition
 *
 * Modèle responsable de la gestion des expéditions.
 * Permet de créer, récupérer, mettre à jour et supprimer des expéditions,
 * ainsi que de récupérer les informations d'une expédition avec les détails du client.
 *
 * @package App\Models
 */
class Expedition extends Model
{
    /**
     * @var string Nom de la table des expéditions
     */
    protected $table = 'expeditions';

    /**
     * Crée une nouvelle expédition.
     *
     * Génère un numéro de suivi unique et insère les informations dans la base.
     *
     * @param array $data Données de l'expédition :
     *                    client_id, ship_email, ship_address, ship_city,
     *                    ship_province, ship_postcode, ship_name, ship_lastname,
     *                    ship_phone, date
     * @param string $status Statut initial de l'expédition (par défaut 'pending')
     * @return string ID de l'expédition créée
     */
    public function create($data, $status = 'pending')
    {
        $tranckingnum = 'TRACK' . strtoupper(uniqid());
        $sql = "INSERT INTO $this->table 
                (client_id, ship_email,ship_address, ship_city, ship_province, ship_postcode, ship_name, ship_lastname, ship_phone, tracking_number, date, status) 
                VALUES (:client_id, :ship_email,:ship_address, :ship_city, :ship_province, :ship_postcode, :ship_name, :ship_lastname,:ship_phone, :tracking_number,:date, :status)";

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

    /**
     * Récupère une expédition avec les informations du client.
     *
     * @param int $expeditionId Identifiant de l'expédition
     * @return array|false Tableau associatif avec les détails du client ou false si non trouvé
     */
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
    // App/Models/Expedition.php

    public function getStatusSummary(): array
    {
        $sql = "
        SELECT
            SUM(CASE WHEN status = 'pending'   THEN 1 ELSE 0 END) AS pending,
            SUM(CASE WHEN status = 'shipped'   THEN 1 ELSE 0 END) AS shipped,
            SUM(CASE WHEN status = 'delivered' THEN 1 ELSE 0 END) AS delivered
        FROM {$this->table}
    ";
        $stmt = $this->db->query($sql);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

}

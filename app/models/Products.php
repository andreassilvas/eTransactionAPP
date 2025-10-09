<?php
namespace App\Models;

use PDO;

/**
 * Class Products
 *
 * Modèle responsable de la gestion des produits.
 * Permet de récupérer, créer et mettre à jour des produits.
 *
 * @package App\Models
 */
class Products extends Model
{
    /**
     * @var string Nom de la table des produits
     */
    protected $table = 'products';

    /**
     * Récupère tous les produits triés par nom.
     *
     * @return array Tableau associatif de tous les produits
     */
    public function all()
    {
        $sql = "SELECT * FROM $this->table ORDER BY name ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère un produit par son ID.
     *
     * @param int $id Identifiant du produit
     * @return array|false Tableau associatif du produit ou false si non trouvé
     */
    public function find($id)
    {
        $sql = "SELECT * FROM $this->table WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Crée un nouveau produit.
     *
     * @param array $data Données du produit : name, category, brand, model, specs, price, stock, warranty_period, support_level, supplier
     * @return string ID du produit créé
     */
    public function create($data)
    {
        $sql = "INSERT INTO $this->table (name, category, brand, model, specs, price, stock, warranty_period, support_level, supplier)
                VALUES (:name, :category, :brand, :model, :specs, :price, :stock, :warranty_period, :support_level, :supplier)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($data);
        return $this->db->lastInsertId();
    }

    /**
     * Met à jour un produit existant.
     *
     * @param int $id Identifiant du produit
     * @param array $data Données à mettre à jour
     * @return bool Succès de l'exécution
     */
    public function update($id, $data)
    {
        $fields = [];
        foreach ($data as $key => $value) {
            $fields[] = "$key = :$key";
        }
        $sql = "UPDATE $this->table SET " . implode(", ", $fields) . " WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $data['id'] = $id;
        return $stmt->execute($data);
    }


}

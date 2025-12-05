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
        $sql = "INSERT INTO $this->table (name, category, brand, model, specs_desc, price, stock, warranty_period, support_level, supplier)
                VALUES (:name, :category, :brand, :model, :specs_desc, :price, :stock, :warranty_period, :support_level, :supplier)";
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

    public function update(int $id, array $data): bool
    {
        $allowedFields = [
            'name',
            'category',
            'brand',
            'model',
            'specs_desc',
            'price',
            'stock',
            'warranty_period',
            'support_level',
            'supplier'
        ];

        $set = [];
        $params = [];

        foreach ($allowedFields as $field) {
            if (array_key_exists($field, $data)) {
                $set[] = "$field = :$field";
                $params[":$field"] = $data[$field];
            }
        }

        if (empty($set))
            return false;

        $sql = "UPDATE {$this->table} SET " . implode(", ", $set) . " WHERE id = :id";
        $params[':id'] = $id;

        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM products WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    // App/Models/Products.php

    public function getStockSummary(): array
    {
        // low stock = 1..5 just as an example threshold
        $sql = "
        SELECT
            COUNT(*) AS total_products,
            SUM(CASE WHEN stock = 0 THEN 1 ELSE 0 END) AS out_of_stock,
            SUM(CASE WHEN stock BETWEEN 1 AND 5 THEN 1 ELSE 0 END) AS low_stock,
            SUM(CASE WHEN stock > 5 THEN 1 ELSE 0 END) AS ok_stock
        FROM {$this->table}
    ";
        $stmt = $this->db->query($sql);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getStockByCategory(): array
    {
        $sql = "
        SELECT COALESCE(category, 'Autre') AS category,
               SUM(stock) AS total_stock
        FROM {$this->table}
        GROUP BY category
        ORDER BY total_stock DESC
        LIMIT 6
    ";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Produits critiques : stock = 0 ou stock entre 1 et 5.
     */
    public function getCriticalProducts(int $limit = 5): array
    {
        $sql = "
        SELECT id, name, category, stock
        FROM {$this->table}
        WHERE stock = 0 OR stock BETWEEN 1 AND 5
        ORDER BY stock ASC, name ASC
        LIMIT :limit
    ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getDistinctOptions($column)
    {
        $sql = "SELECT DISTINCT $column FROM {$this->table} 
            WHERE $column IS NOT NULL AND $column != '' 
            ORDER BY $column ASC";

        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

}

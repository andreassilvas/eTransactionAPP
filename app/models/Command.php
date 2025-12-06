<?php
namespace App\Models;

/**
 * Class Command
 *
 * Modèle responsable de la gestion des commandes (expéditions) des clients.
 * Permet de récupérer les expéditions et les informations associées aux paiements et produits.
 *
 * @package App\Models
 */

class Command extends Model
{
    /**
     * Récupère toutes les commandes/expéditions d'un client.
     *
     * Pour chaque expédition, inclut :
     * - Informations sur l'expédition (date, statut, nom du destinataire, email)
     * - Informations sur le paiement (montant, statut, méthode)
     * - Liste des produits associés (quantité, nom, prix unitaire)
     *
     * @param int $clientId Identifiant du client
     * @return array Tableau associatif des expéditions
     */
    public function getByClientId($clientId)
    {
        $sql = "
            SELECT 
                e.id AS expedition_id,
                e.date AS expedition_date,
                e.status AS expedition_status,
                e.ship_name AS expedition_name,
                e.ship_lastname AS expedition_lastname,
                e.ship_email AS expedition_email,
                p.amount AS payment_amount,
                p.status AS payment_status,
                p.method AS payment_method,
                GROUP_CONCAT(CONCAT(i.quantity, 'x ', pr.name, ' @ ', i.unit_price) SEPARATOR ', ') AS products
            FROM expeditions e
            LEFT JOIN payments p ON p.expedition_id = e.id
            LEFT JOIN expedition_items i ON i.expedition_id = e.id
            LEFT JOIN products pr ON pr.id = i.product_id
            WHERE e.client_id = :client_id
            GROUP BY e.id
            ORDER BY e.id DESC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['client_id' => $clientId]);

        // Retourne la liste des commandes sous forme de tableau associatif
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}

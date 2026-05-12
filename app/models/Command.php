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
     * @param int $companyId Identifiant de la société
     * @return array Tableau associatif des expéditions
     */
    public function getByCompanyId($companyId)
    {
        $sql = "
            SELECT 
                e.id AS expedition_id,
                e.date AS expedition_date,
                e.status AS expedition_status,
                u.name AS expedition_name,
                u.lastname AS expedition_lastname,
                u.email AS expedition_email,
                p.amount AS payment_amount,
                p.status AS payment_status,
                p.method AS payment_method,
                GROUP_CONCAT(CONCAT(i.quantity, 'x ', pr.name, ' @ ', i.unit_price) SEPARATOR ', ') AS products
            FROM expeditions e
            JOIN users u ON e.client_id = u.id
            LEFT JOIN payments p ON p.expedition_id = e.id
            LEFT JOIN expedition_items i ON i.expedition_id = e.id
            LEFT JOIN products pr ON pr.id = i.product_id
            WHERE e.company_id = :company_id
            GROUP BY e.id
            ORDER BY e.date DESC, e.id DESC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['company_id' => $companyId]);

        // Retourne la liste des commandes sous forme de tableau associatif
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    public function getByPaymentId($paymentId)
    {
        $sql = "
        SELECT 
            i.quantity,
            i.unit_price AS price,
            pr.name AS product_name
        FROM payments p
        JOIN expeditions e ON e.id = p.expedition_id
        JOIN expedition_items i ON i.expedition_id = e.id
        JOIN products pr ON pr.id = i.product_id
        WHERE p.id = :payment_id
    ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['payment_id' => $paymentId]);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}

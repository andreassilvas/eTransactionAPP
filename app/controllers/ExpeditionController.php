<?php
namespace App\Controllers;

use App\Models\Database;

/**
 * Class ExpeditionController
 *
 * Contrôleur responsable de la gestion des informations d'expédition.
 * Il permet de récupérer l'adresse de facturation d'un client ou
 * d'enregistrer une nouvelle adresse saisie avant la redirection vers le paiement.
 *
 * @package App\Controllers
 */

class ExpeditionController
{
    /**
     * Traite les données du formulaire d'expédition.
     *
     * - Si l'utilisateur choisit d'utiliser son adresse de facturation,
     *   elle est récupérée depuis la base de données et stockée dans la session.
     * - Sinon, les données du formulaire sont validées, formatées et enregistrées
     *   dans la session avant la redirection vers la page de paiement.
     *
     * @return void
     */
    public function store()
    {
        // Connexion à la base de données (la session est déjà démarrée via init.php)
        $db = Database::getConnection();

        /**
         * Étape 1 : Vérifie si l'utilisateur souhaite utiliser son adresse de facturation existante
         */
        if (isset($_POST['use_billing_address'])) {
            $clientId = $_SESSION['client_id'] ?? null;

            if ($clientId) {
                // Récupère les informations du client depuis la base de données
                $stmt = $db->prepare("SELECT * FROM clients WHERE id = :id LIMIT 1");
                $stmt->execute(['id' => $clientId]);
                $client = $stmt->fetch(\PDO::FETCH_OBJ);

                // Si le client est trouvé, stocke ses informations dans la session
                if ($client) {
                    $_SESSION['expedition_data'] = [
                        'email' => $client->email,
                        'name' => $client->name,
                        'lastname' => $client->lastname,
                        'phone' => $client->phone,
                        'address' => $client->address,
                        'city' => $client->city,
                        'province' => $client->province,
                        'postcode' => $client->postcode
                    ];

                    // Redirige vers la page de paiement
                    header("Location: /eTransactionAPP/public/payment");
                    exit;
                }
            }

            // Si aucun client n’est trouvé, redirige vers la page de connexion
            header("Location: /eTransactionAPP/public/login");
            exit;
        }

        /**
         * Étape 2 : Collecte et nettoie les données du formulaire
         */
        $email = strtolower(trim($_POST['email'] ?? ''));
        $name = ucfirst(strtolower(trim($_POST['name'] ?? '')));
        $lastname = ucfirst(strtolower(trim($_POST['lastname'] ?? '')));
        $phoneRaw = preg_replace('/\D/', '', $_POST['phone'] ?? '');
        $extention = trim($_POST['extention'] ?? '');
        $addressRaw = trim($_POST['address'] ?? '');
        $city = ucfirst(strtolower(trim($_POST['city'] ?? '')));
        $province = trim($_POST['province'] ?? '');
        $postcode = strtoupper(trim($_POST['postcode'] ?? ''));

        /**
         * Étape 3 : Validation des champs
         */
        $errors = [];
        if (!$name)
            $errors[] = "Le prénom est requis.";
        if (!$lastname)
            $errors[] = "Le nom de famille est requis.";
        if (!$phoneRaw || strlen($phoneRaw) !== 10)
            $errors[] = "Le téléphone doit contenir 10 chiffres.";
        if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL))
            $errors[] = "L'email est invalide.";
        if (!$addressRaw)
            $errors[] = "L'adresse est requise.";
        if (!$city)
            $errors[] = "La ville est requise.";
        if (!$province)
            $errors[] = "La province est requise.";
        if (!$postcode)
            $errors[] = "Le code postal est requis.";

        // Si des erreurs sont détectées, elles sont sauvegardées en session et l'utilisateur est redirigé
        if (!empty($errors)) {
            $_SESSION['expedition_errors'] = $errors;
            header("Location: /eTransactionAPP/public/expedition");
            exit;
        }

        /**
         * Étape 4 : Mise en forme du téléphone
         */
        if (strlen($phoneRaw) === 10) {
            $phone = sprintf(
                "(%s) %s %s",
                substr($phoneRaw, 0, 3),
                substr($phoneRaw, 3, 3),
                substr($phoneRaw, 6, 4)
            );
        } else {
            $phone = $phoneRaw;
        }

        /**
         * Étape 5 : Mise en forme de l’adresse
         * - Met la première lettre en majuscule
         * - Laisse le reste en minuscules
         */
        $address = preg_replace_callback('/([a-z])/', function ($matches) {
            static $first = true;
            if ($first) {
                $first = false;
                return strtoupper($matches[1]);
            }
            return $matches[1];
        }, strtolower($addressRaw));

        /**
         * Étape 6 : Stocke les informations d’expédition dans la session
         */
        $_SESSION['expedition_data'] = [
            'email' => $email,
            'name' => $name,
            'lastname' => $lastname,
            'phone' => $phone,
            'extention' => $extention,
            'address' => $address,
            'city' => $city,
            'province' => $province,
            'postcode' => $postcode
        ];

        // Rediriger vers la page de paiement
        header("Location: /eTransactionAPP/public/payment");
        exit;
    }
}

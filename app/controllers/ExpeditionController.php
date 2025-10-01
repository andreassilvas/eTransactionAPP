<?php
namespace App\Controllers;

use App\Models\Database;

class ExpeditionController
{
    public function store()
    {
        // DB connection (session already started via init.php)
        $db = Database::getConnection();

        // Check if user clicked "use billing address"
        if (isset($_POST['use_billing_address'])) {
            $clientId = $_SESSION['client_id'] ?? null;

            if ($clientId) {
                $stmt = $db->prepare("SELECT * FROM clients WHERE id = :id LIMIT 1");
                $stmt->execute(['id' => $clientId]);
                $client = $stmt->fetch(\PDO::FETCH_OBJ);

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

                    header("Location: /eTransactionAPP/public/payment");
                    exit;
                }
            }

            // If no client, redirect to login
            header("Location: /eTransactionAPP/public/login");
            exit;
        }

        // Collect POST data
        $email = strtolower(trim($_POST['email'] ?? ''));
        $name = ucfirst(strtolower(trim($_POST['name'] ?? '')));
        $lastname = ucfirst(strtolower(trim($_POST['lastname'] ?? '')));
        $phoneRaw = preg_replace('/\D/', '', $_POST['phone'] ?? '');
        $extention = trim($_POST['extention'] ?? '');
        $addressRaw = trim($_POST['address'] ?? '');
        $city = ucfirst(strtolower(trim($_POST['city'] ?? '')));
        $province = trim($_POST['province'] ?? '');
        $postcode = strtoupper(trim($_POST['postcode'] ?? ''));

        // Server-side validation
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

        if (!empty($errors)) {
            $_SESSION['expedition_errors'] = $errors;
            header("Location: /eTransactionAPP/public/expedition");
            exit;
        }

        // Format phone
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
        // Format address
        $address = preg_replace_callback('/([a-z])/', function ($matches) {
            static $first = true;
            if ($first) {
                $first = false;
                return strtoupper($matches[1]);
            }
            return $matches[1];
        }, strtolower($addressRaw));

        // Store in session
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

        // Redirect to payment
        header("Location: /eTransactionAPP/public/payment");
        exit;
    }
}

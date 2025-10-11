<?php
namespace App\Models;

use PDO;

require_once __DIR__ . '/../app/config.php';

class Database
{
    // Connexion PDO partagée
    private static $connection = null;

    // Retourne la connexion PDO unique (singleton)
    public static function getConnection()
    {
        if (self::$connection === null) {
            try {
                self::$connection = new PDO(
                    "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8",
                    DB_USER,
                    DB_PASS
                );
                // Définir le mode d'erreur sur Exception
                self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (\PDOException $e) {  // <-- add leading backslash
                die("Database connection failed: " . $e->getMessage());
            }
        }
        return self::$connection;
    }
}

<?php
namespace App\Models;

use PDO;

class Database
{
    private static $connection = null;

    public static function getConnection()
    {
        if (self::$connection === null) {
            try {
                self::$connection = new PDO(
                    "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8",
                    DB_USER,
                    DB_PASS
                );
                self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (\PDOException $e) {  // <-- add leading backslash
                die("Database connection failed: " . $e->getMessage());
            }
        }
        return self::$connection;
    }
}

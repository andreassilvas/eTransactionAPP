<?php
namespace App\Models;

require_once __DIR__ . '/../app/config.php';
require_once __DIR__ . '/../app/db.php';

class Model
{
    // Propriété pour stocker la connexion à la base de données
    protected $db;

    // Constructeur : initialise la connexion à la base de données
    public function __construct()
    {
        $this->db = Database::getConnection();
    }
}

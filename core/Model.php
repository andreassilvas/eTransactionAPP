<?php
namespace App\Models;

require_once __DIR__ . '/../app/config.php';
require_once __DIR__ . '/../app/db.php';

class Model
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }
}

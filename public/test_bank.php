<?php
require __DIR__ . '/../app/config.php';
require __DIR__ . '/../app/db.php';

use App\Models\Database;

// Assuming client_id is stored in session
session_start();
$clientId = $_SESSION['client_id'] ?? null;

if (!$clientId) {
    die("Client not logged in.");
}
$clientId = 1; // test value

try {
    $db = Database::getConnection();

    $sql = "SELECT * FROM bank_transactions WHERE client_id = :client_id ORDER BY transaction_date DESC";

    $stmt = $db->prepare($sql);
    $stmt->bindParam(':client_id', $clientId, PDO::PARAM_INT);
    $stmt->execute();

    $transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "<pre>";
    var_dump($transactions);
    echo "</pre>";

} catch (\PDOException $e) {
    echo "DB Error: " . $e->getMessage();
}
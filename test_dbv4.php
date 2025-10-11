<?php

try {
    $pdo = new PDO(
        "mysql:host=fdb1033.awardspace.net;dbname=4693353_etransactionnelle;charset=utf8",
        "4693353_etransactionnelle",
        "trans00su2025!"
    );
    echo "Connected!";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

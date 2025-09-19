<?php
require_once __DIR__ . '/../init.php';

if (isset($_SESSION['client_id'])) {
    header("Location: /eTransactionAPP/public/expedition");
    exit;
}

<?php
namespace App\Controllers;

use App\Models\Command;

class CommandController
{
    public function index()
    {
        if (!isset($_SESSION['client_id'])) {
            header("Location: /eTransactionAPP/public/login");
            exit;
        }

        $clientId = $_SESSION['client_id'];
        $commandModel = new Command();
        $commands = $commandModel->getByClientId($clientId);

        include __DIR__ . '/../views/commands/index.php';

        //Debugging line
        // echo "<pre>";
        // print_r($commands);
        // echo "</pre>";
        // error_log(print_r($commands, true));
    }
}

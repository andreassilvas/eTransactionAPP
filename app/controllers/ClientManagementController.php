<?php

namespace App\Controllers;
use App\Models\Client;


class ClientManagementController
{
    private Client $clients;
    public function __construct()
    {
        $this->clients = new Client();
    }

    public function index()
    {
        require __DIR__ . "/../Views/clients/index.php";
    }

    // JSON endpoints (AJAX)
    public function list()
    {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($this->clients->all(), JSON_UNESCAPED_UNICODE);
    }

    public function store()
    {
        $data = $this->payload();
        $id = $this->clients->create($data);
        $this->json(['ok' => true, 'id' => $id]);
    }

    public function update()
    {
        $data = $this->payload();
        $this->clients->updateById((int) $data['id'], $data);
        $this->json(['ok' => true]);
    }

    public function delete()
    {
        $id = (int) ($_GET['id'] ?? 0);
        $this->clients->deleteById($id);
        $this->json(['ok' => true]);
    }

    private function payload()
    {
        $raw = file_get_contents('php://input');
        return $raw ? json_decode($raw, true) : $_POST;
    }
    private function json($x)
    {
        header('Content-Type: application/json');
        echo json_encode($x);
    }

}

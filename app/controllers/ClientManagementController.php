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
        $rows = $this->clients->all();

        // Mask passwords before sending to the browser
        foreach ($rows as &$r) {
            $r['password'] = '••••••'; // mask (never show hash)
        }

        $this->json($rows);
    }


    public function store()
    {
        $data = $this->payload();

        if (isset($data['email'])) {
            $data['email'] = strtolower(trim($data['email']));
        }

        foreach (['name', 'lastname', 'email', 'password'] as $k) {
            if (empty($data[$k]))
                return $this->json(['ok' => false, 'error' => "Missing $k"], 422);
        }

        if ($this->clients->existsByEmail($data['email'])) {
            return $this->json(['ok' => false, 'error' => 'Email already exists'], 409);
        }

        $id = $this->clients->create($data);
        $this->json(['ok' => true, 'id' => $id], 201);
    }

    public function update()
    {
        $data = $this->payload();
        $id = (int) ($data['id'] ?? 0);
        if ($id <= 0)
            return $this->json(['ok' => false, 'error' => 'Missing id'], 422);

        if (isset($data['email']))
            $data['email'] = strtolower(trim($data['email']));
        if (array_key_exists('password', $data) && ($data['password'] === '' || $data['password'] === null)) {
            unset($data['password']); // do not overwrite when empty
        }

        $rows = $this->clients->updateById($id, $data);
        return $this->json(['ok' => true, 'rows' => $rows], 200);
    }



    public function delete()
    {
        $id = (int) ($_GET['id'] ?? 0);
        if ($id <= 0)
            return $this->json(['ok' => false, 'error' => 'Missing id'], 422);

        $this->clients->deleteById($id);
        $this->json(['ok' => true], 200);
    }

    /* ---------- helpers ---------- */

    private function payload()
    {
        $raw = file_get_contents('php://input');
        return $raw ? (array) json_decode($raw, true) : $_POST;
    }

    private function json($x, int $code = 200)
    {
        http_response_code($code);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($x, JSON_UNESCAPED_UNICODE);
    }
}

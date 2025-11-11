<?php

namespace App\Controllers;

use App\Models\Province;
use App\Models\City;

class GeoController
{
    private Province $provinces;
    private City $cities;

    public function __construct()
    {
        $this->provinces = new Province();
        $this->cities = new City();
    }

    /**
     * GET /geo/provinces
     * Returns: [{"id": 11, "code": "QC", "name": "Québec"}, ...]
     */
    public function provinces()
    {
        $this->json($this->provinces->allActive());
    }

    /**
     * GET /geo/provinces/{code}/cities?search=&limit=&offset=
     * Returns: { items: [...], total: 123, limit: 50, offset: 0 }
     */
    public function citiesByProvince()
    {
        // If you use a simple router, grab {code} from $_GET or your router var
        $code = strtoupper($_GET['code'] ?? '');
        $search = $_GET['search'] ?? '';
        $limit = max(1, min(200, (int) ($_GET['limit'] ?? 50)));
        $offset = max(0, (int) ($_GET['offset'] ?? 0));

        $this->json($this->cities->byProvinceCode($code, $search, $limit, $offset));
    }

    /**
     * GET /geo/cities?province=QC&search=&limit=&offset=
     * Returns: { items: [...], total: 123, limit: 50, offset: 0 }
     */
    public function cities()
    {
        $prov = isset($_GET['province']) ? strtoupper($_GET['province']) : null;
        $search = $_GET['search'] ?? '';
        $limit = max(1, min(200, (int) ($_GET['limit'] ?? 50)));
        $offset = max(0, (int) ($_GET['offset'] ?? 0));

        $this->json($this->cities->listing($prov, $search, $limit, $offset));
    }

    /**
     * GET /geo/cities/show?id=123
     * Returns: { "id": 123, "name": "...", "province_code": "QC" }
     */
    public function cityShow()
    {
        $id = (int) ($_GET['id'] ?? 0);
        $row = $this->cities->getById($id);
        if (!$row)
            return $this->json(['error' => 'Not found'], 404);
        $this->json($row);
    }

    // ---- helpers (same spirit as your ClientManagementController) ----
    private function payload()
    {
        $raw = file_get_contents('php://input');
        return $raw ? json_decode($raw, true) : $_POST;
    }

    private function json($data, int $code = 200)
    {
        http_response_code($code);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
}

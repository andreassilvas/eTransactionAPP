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
     * Returns: [{"id": 1,"code": "AB","name": "Alberta"}, ...]
     */
    public function provinces()
    {
        $this->json($this->provinces->allActive());
    }

    /**
     * GET /geo/provinces/{code}/cities?search=&limit=&offset=
     * Returns: { items: [...], total: 12, limit: 50, offset: 0 }
     */
    public function citiesByProvince()
    {
        $code = strtoupper($_GET['code'] ?? '');
        $search = $_GET['search'] ?? '';
        $limit = max(1, min(200, (int) ($_GET['limit'] ?? 50)));
        $offset = max(0, (int) ($_GET['offset'] ?? 0));

        $this->json($this->cities->byProvinceCode($code, $search, $limit, $offset));
    }

    /**
     * GET /geo/cities?province=QC&search=&limit=&offset=
     * Returns: { items: [...],  "limit": 1,"offset": 0,"total": 5}
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
     * GET /geo/cities/show?id=12
     * Returns: {"id":12,"name":"Hamilton","province_code":"ON"}
     */
    public function cityShow()
    {
        $id = (int) ($_GET['id'] ?? 0);
        $row = $this->cities->getById($id);
        if (!$row)
            return $this->json(['error' => 'Not found'], 404);
        $this->json($row);
    }

    private function json($data, int $code = 200)
    {
        http_response_code($code);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
}

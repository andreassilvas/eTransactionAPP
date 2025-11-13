<?php
namespace App\Controllers;

use App\Models\Products;
use App\Models\Expedition;
use App\Models\ExpeditionItem;

class DashboardController
{
    public function index(): void
    {
        $productModel = new Products();
        $expeditionModel = new Expedition();
        $expeditionItemModel = new ExpeditionItem();

        $stockSummary = $productModel->getStockSummary();
        $stockByCategory = $productModel->getStockByCategory();
        $criticalProducts = $productModel->getCriticalProducts(5);

        $expStatus = $expeditionModel->getStatusSummary();
        $topShipped = $expeditionItemModel->getTopShippedProducts(5);

        // Pass everything to the view
        require __DIR__ . '/../Views/dashboard/index.php';
    }
}

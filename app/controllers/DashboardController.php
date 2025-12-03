<?php
namespace App\Controllers;

use App\Models\Products;
use App\Models\Expedition;
use App\Models\ExpeditionItem;

class DashboardController
{
    public function index(string $view = 'dashboard/index.php'): void
    {
        $productModel = new Products();
        $expeditionModel = new Expedition();
        $expeditionItemModel = new ExpeditionItem();

        $stockSummary = $productModel->getStockSummary();
        $stockByCategory = $productModel->getStockByCategory();
        $criticalProducts = $productModel->getCriticalProducts(5);

        $expStatus = $expeditionModel->getStatusSummary();
        $totalExpeditions = $expeditionItemModel->getTotalExpeditionQuantity();
        $delivered = $expeditionItemModel->getTopProductsByStatus('delivered', 5);
        $shipped = $expeditionItemModel->getTopProductsByStatus('shipped', 5);
        $pending = $expeditionItemModel->getTopProductsByStatus('pending', 5);

        // Pass everything to the view
        require __DIR__ . '/../Views/' . $view;
    }
}

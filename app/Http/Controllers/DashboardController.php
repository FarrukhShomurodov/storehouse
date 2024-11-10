<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use App\Services\SalesStatsService;
use App\Services\CancellationStatsService;
use App\Services\StockStatsService;

class DashboardController
{
    protected $salesStatsService;
    protected $cancellationStatsService;
    protected $stockStatsService;

    public function __construct(SalesStatsService $salesStatsService, CancellationStatsService $cancellationStatsService, StockStatsService $stockStatsService)
    {
        $this->salesStatsService = $salesStatsService;
        $this->cancellationStatsService = $cancellationStatsService;
        $this->stockStatsService = $stockStatsService;
    }

    public function index(): View
    {
        // Получаем статистику
        $salesStats = $this->salesStatsService->getSalesStats();
        $cancellationStats = $this->cancellationStatsService->getCancellationStats();
        $stockStats = $this->stockStatsService->getStockStats();

        // Передаем данные в представление
        return view('admin.dashboard', compact('salesStats', 'cancellationStats', 'stockStats'));
    }
}

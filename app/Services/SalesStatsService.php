<?php

// app/Services/SalesStatsService.php

namespace App\Services;

use App\Models\Sale;

class SalesStatsService
{
    public function getSalesStats()
    {
        // Пример: данные о продажах за день, неделю, месяц
        $dailySales = Sale::whereDate('created_at', today())->sum('price');
        $weeklySales = Sale::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->sum('price');
        $monthlySales = Sale::whereMonth('created_at', now()->month)->sum('price');
        $totalSales = Sale::sum('price');

        // Данные для графика
        $salesOverTime = $this->getSalesOverTime();
        $timeLabels = $this->getTimeLabels();

        return [
            'daily_sales' => $dailySales,
            'weekly_sales' => $weeklySales,
            'monthly_sales' => $monthlySales,
            'total_sales' => $totalSales,
            'sales_over_time' => $salesOverTime->toArray(),
            'time_labels' => $timeLabels->toArray(),
        ];
    }

    private function getSalesOverTime()
    {
        // Пример: Данные по продажам за последние 7 дней
        return Sale::selectRaw('DATE(created_at) as date, SUM(price) as total')
            ->whereDate('created_at', '>', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date');
    }

    private function getTimeLabels()
    {
        // Пример: Возвращаем массив дат для оси X
        return collect(range(0, 6))->map(function ($i) {
            return now()->subDays(6 - $i)->format('d M');
        });
    }
}

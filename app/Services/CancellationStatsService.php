<?php
// app/Services/CancellationStatsService.php

namespace App\Services;

use App\Models\SaleCancellationLog;

class CancellationStatsService
{
    public function getCancellationStats()
    {
        // Пример: данные по отменам за день, неделю, месяц
        $dailyCancellations = SaleCancellationLog::whereDate('created_at', today())->count();
        $weeklyCancellations = SaleCancellationLog::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
        $monthlyCancellations = SaleCancellationLog::whereMonth('created_at', now()->month)->count();
        $totalCancellations = SaleCancellationLog::count();

        // Данные для графика
        $cancellationsOverTime = $this->getCancellationsOverTime();
        $timeLabels = $this->getTimeLabels();

        return [
            'daily_cancellations' => $dailyCancellations,
            'weekly_cancellations' => $weeklyCancellations,
            'monthly_cancellations' => $monthlyCancellations,
            'total_cancellations' => $totalCancellations,
            'cancellations_over_time' => $cancellationsOverTime->toArray(),
            'time_labels' => $timeLabels->toArray(),
        ];
    }

    private function getCancellationsOverTime()
    {
        // Пример: Данные по отменам за последние 7 дней
        return SaleCancellationLog::selectRaw('DATE(created_at) as date, COUNT(*) as total')
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

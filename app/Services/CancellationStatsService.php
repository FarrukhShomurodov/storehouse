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
        // Получаем данные по отменам за последние 7 дней
        $cancellationsData = SaleCancellationLog::selectRaw('DATE(created_at) as date, COUNT(*) as total')
        ->whereDate('created_at', '>', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Плоский массив для данных по отменам
        $cancellationsOverTime = [];

        // Получаем даты отмен и количество в ассоциативный массив
        $cancellationDates = $cancellationsData->pluck('total', 'date')->toArray();

        // Генерируем метки времени для последних 7 дней
        $timeLabels = collect(range(0, 6))->map(function ($i) {
            return now()->subDays(6 - $i)->format('Y-m-d'); // Форматируем в 'Y-m-d' для поиска по ключу
        });

        // Заполняем массив данными для графика, используя 0 для отсутствующих данных
        foreach ($timeLabels as $date) {
            $cancellationsOverTime[] = isset($cancellationDates[$date]) ? (int)$cancellationDates[$date] : 0;
        }

        return collect($cancellationsOverTime);
    }


    private function getTimeLabels()
    {
        // Пример: Возвращаем массив дат для оси X
        return collect(range(0, 6))->map(function ($i) {
            return now()->subDays(6 - $i)->format('d M');
        });
    }
}

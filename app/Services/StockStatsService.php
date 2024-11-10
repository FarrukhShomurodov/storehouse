<?php
// app/Services/StockStatsService.php

namespace App\Services;

use App\Models\Product;

class StockStatsService
{
    public function getStockStats()
    {
        $products = Product::with(['units', 'sales'])->get();

        $stockStats = $products->map(function ($product) {
            $soldUnits = $product->sales->count(); // Проданные единицы
            $cancelledUnits = $product->units->flatMap(function ($unit) {
                return $unit->cancelLog; // Отмененные единицы
            })->count();

            return [
                'product_name' => $product->name,
                'available_units' => $product->quantity - $soldUnits, // Остатки
                'sold_units' => $soldUnits,
                'cancelled_units' => $cancelledUnits,
            ];
        });

        // Данные для графиков
        $soldUnits = $stockStats->pluck('sold_units');
        $availableUnits = $stockStats->pluck('available_units');
        $productNames = $stockStats->pluck('product_name');

        return [
            'stock_stats' => $stockStats->toArray(),
            'sold_units' => $soldUnits->toArray(),
            'available_units' => $availableUnits->toArray(),
            'product_names' => $productNames->toArray(),
        ];
    }
}

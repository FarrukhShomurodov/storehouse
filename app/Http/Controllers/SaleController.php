<?php

namespace App\Http\Controllers;

use App\Models\ProductUnit;
use App\Models\Sale;
use App\Models\SaleCancellationLog;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class SaleController
{
    public function sell($serialNumber): View
    {
        $unit = ProductUnit::query()
            ->where('serial_number', $serialNumber)
            ->where('sold', false)
            ->first();

        if (!$unit || $unit->sold) {
            return view('admin.products.confirmed')->withErrors(
                ['Этот товар уже был продан или не существует.']
            );
        }

        $unit->update([
            'sold' => true,
            'sold_at' => now()
        ]);

        $product = $unit->product;
        $product->quantity -= 1;
        $product->save();

        Sale::query()->create([
            'product_id' => $product->id,
            'product_unit_id' => $unit->id,
            'price' => $product->price,
        ]);

        return view('admin.products.confirmed', compact('product'))->with(
            'success',
            'Товар успешно продан.'
        );
    }

    public function cancel(Request $request, $serialNumber): View
    {
        $unit = ProductUnit::query()
            ->where('serial_number', $serialNumber)
            ->where('sold', true)
            ->first();

        if (!$unit) {
            return view('admin.products.confirmed')->withErrors(
                ['Товар не был продан или не найден.']
            );
        }

        $unit->update([
            'sold' => false
        ]);

        $sale = $unit->sale;
        if ($sale) {
            $sale->delete();
        }

        SaleCancellationLog::query()->create([
            'product_id' => $unit->product->id,
            'product_unit_id' => $unit->id,
            'reason' => $request->input('reason'),
        ]);

        $product = $unit->product;
        $product->quantity += 1;
        $product->save();

        return view(
            'admin.products.confirmed',
            compact('product')
        )->with(
            'success',
            'Продажа отменена, товар возвращён в систему.'
        );
    }
}

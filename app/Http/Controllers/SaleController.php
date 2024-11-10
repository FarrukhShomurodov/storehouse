<?php

namespace App\Http\Controllers;

use App\Models\ProductUnit;
use Illuminate\Contracts\View\View;

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

        return view('admin.products.confirmed', compact('product'))->with(
            'success',
            'Товар успешно продан.'
        );
    }

    public function cancel($serialNumber): View
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

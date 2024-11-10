<?php

namespace App\Http\Controllers;

use App\Models\ProductUnit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class ProductUnitController
{
    public function destroy(ProductUnit $productUnit): RedirectResponse
    {
        Storage::disk('public')->delete($productUnit->qr_code);
        $productUnit->delete();

        return redirect()->back();
    }
}

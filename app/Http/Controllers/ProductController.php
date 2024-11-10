<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Models\ProductUnit;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ProductController
{
    public function index(): View
    {
        $products = Product::query()->get();
        return view('admin.products.index', compact('products'));
    }

    public function create(): View
    {
        return view('admin.products.create');
    }

    public function store(ProductRequest $request): RedirectResponse
    {
        $product = Product::query()->create($request->validated());

        for ($i = 0; $i < $request->quantity; $i++) {
            $uuid = (string)Str::uuid();
            $filePath = 'qrcodes/' . $uuid . '.svg';

            $qrCode = QrCode::format('svg')->size(300)->generate(
                url("product/$product->id/unit/$uuid/confirm")
            );

            $unit = ProductUnit::query()->create([
                'product_id' => $product->id,
                'serial_number' => $uuid,
                'qr_code' => $filePath
            ]);
            Storage::disk('public')->put($filePath, $qrCode);
        }

        return redirect()->route('products.index')->with('success', 'Продукты добавлены и QR-коды сгенерированы!');
    }

    public function show($product_id): Factory|Application|View|RedirectResponse
    {
        $product = Product::with('units')->find($product_id);

        if (!$product) {
            return redirect()->route('admin.products.show')->with('error', 'Продукт не найден!');
        }

        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product): View
    {
        return view('admin.products.edit', compact('product'));
    }

    public function update(ProductRequest $request, Product $product): RedirectResponse
    {
        $validated = $request->validated();
        $currentQuantity = $product->quantity;

        if ((int)$validated['quantity'] < $currentQuantity) {
            $quantityToDelete = $currentQuantity - (int)$validated['quantity'];

            $unitsToDelete = ProductUnit::where('product_id', $product->id)
                ->latest()
                ->take($quantityToDelete)
                ->get();

            foreach ($unitsToDelete as $unit) {
                Storage::disk('public')->delete($unit->qr_code);
                $unit->delete();
            }
        }

        $product->quantity = (int)$validated['quantity'];

        if ((int)$validated['quantity'] > $currentQuantity) {
            $newUnitsCount = (int)$validated['quantity'] - $currentQuantity;

            for ($i = 0; $i < $newUnitsCount; $i++) {
                $uuid = (string)Str::uuid();
                $filePath = 'qrcodes/' . $uuid . '.svg';

                $qrCode = QrCode::format('svg')->size(300)->generate(
                    url("product/$product->id/unit/$uuid/confirm")
                );

                $unit = ProductUnit::query()->create([
                    'product_id' => $product->id,
                    'serial_number' => $uuid,
                    'qr_code' => $filePath
                ]);

                Storage::disk('public')->put($filePath, $qrCode);
            }
        }

        $product->save();
        return redirect()->route('products.index')->with('success', 'Продукт успешно обновлен! QR-коды обновлены!');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $units = ProductUnit::where('product_id', $product->id)->get();

        foreach ($units as $unit) {
            Storage::disk('public')->delete($unit->qr_code);
            $unit->delete();
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Продукт и его QR-коды успешно удалены!');
    }
}

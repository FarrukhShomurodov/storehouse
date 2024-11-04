<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

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

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:products,sku|max:50',
            'quantity' => 'required|integer',
            'price' => 'required|numeric',
        ]);

        $product = Product::query()->create($request->all());
        $product->generateQrCode();

        return redirect()->route('products.index')->with('success', 'Продукт успешно создан!');
    }

    public function show(Product $product): View
    {
        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product): View
    {
        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:50|unique:products,sku,' . $product->id,
            'quantity' => 'required|integer',
            'price' => 'required|numeric',
        ]);

        $product->update($request->all());
        $product->generateQrCode();

        return redirect()->route('products.index')->with('success', 'Продукт успешно обновлен!');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $filePath = str_replace('storage/', '', $product->qr_code);

        if ($product->qr_code && Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Продукт успешно удален!');
    }

    public function sale(Product $product): Factory|View|Application
    {
        if ($product->quantity > 0) {
            $product->quantity -= 1;
            $product->save();

            return view('admin.products.confirmation', [
                'product' => $product,
                'message' => 'Товар успешно продан!',
            ]);
        } else {
            return view('admin.products.confirmation', [
                'product' => $product,
                'message' => 'Товар закончился!',
            ]);
        }
    }

    public function confirmSale(Product $product): Factory|View|Application
    {
        if ($product->quantity > 0) {
            return view('admin.products.confirmation', [
                'product' => $product,
                'message' => 'Товар успешно продан!',
            ]);
        } else {
            return view('admin.products.confirmation', [
                'product' => $product,
                'message' => 'Товар закончился!',
            ]);
        }
    }

    public function downloadQrCode(Product $product): BinaryFileResponse|RedirectResponse
    {
        $filePath = str_replace('storage/', '', $product->qr_code);

        if (!$product->qr_code || !Storage::disk('public')->exists($filePath)) {
            return redirect()->back()->withErrors('QR-код не найден.');
        }

        $fullPath = Storage::disk('public')->path($filePath);

        return response()->download($fullPath, "{$product->sku}_qrcode.svg");
    }
}

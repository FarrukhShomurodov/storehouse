<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Jobs\GenerateProductUnit;
use App\Models\Product;
use App\Models\ProductUnit;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use ZipArchive;

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
        $validated = $request->validated();
        $product = Product::query()->create($validated);

        GenerateProductUnit::dispatch($product, $validated['quantity']);

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
            GenerateProductUnit::dispatch($product, (int)$validated['quantity']);
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

    public function printQRCodes($product)
    {
        $product = Product::with('units')->find($product);

        if (!$product) {
            return redirect()->route('products.index')->with('error', 'Продукт не найден!');
        }

        return view('admin.products.print', compact('product'));
    }

    public function downloadQRCodes($product_id)
    {
        $product = Product::with('units')->find($product_id);

        if (!$product) {
            return redirect()->route('products.index')->with('error', 'Продукт не найден!');
        }
        $zipFileName = 'qr_codes_' . $product->name . '.zip';
        $zipFilePath = storage_path('app/public/' . $zipFileName);

        $zip = new ZipArchive;
        if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            foreach ($product->units as $unit) {
                if(!$unit->sold){
                    $qrCodePath = Storage::disk('public')->path($unit->qr_code);
                    if (file_exists($qrCodePath)) {
                        $zip->addFile($qrCodePath, 'qr_codes/qr_code_' . $unit->serial_number . '.png');
                    }
                }
            }
            $zip->close();
        } else {
            return redirect()->route('products.index')->with('error', 'Не удалось создать ZIP архив.');
        }

        return response()->download($zipFilePath)->deleteFileAfterSend(true);
    }

}

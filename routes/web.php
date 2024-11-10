<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductUnitController;
use App\Http\Controllers\SaleController;
use Illuminate\Support\Facades\Route;

Route::get('login', [AuthController::class, 'showLoginForm']);
Route::post('login', [AuthController::class, 'login'])->name('login');

Route::group(['middleware' => 'auth'], function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Products
    Route::resource('products', ProductController::class);

    // Units Products
    Route::delete('/unit-products/{productUnit}', [ProductUnitController::class, 'destroy'])->name(
        'unit.product.delete'
    );

    // Sell
    Route::get('info/{serialNumber}/sell')->name('info');
    Route::get('product/{serialNumber}/sell', [SaleController::class, 'sell'])->name('sell');
    Route::get('product/{serialNumber}/cancel-sale', [SaleController::class, 'cancel'])->name('cancel');

    Route::get('test', function () {
        $unit = \App\Models\ProductUnit::with('product')->first();
        $product = $unit->product;

        return view('admin.products.confirm', compact('unit', 'product'));
    });

});

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
    Route::get('/products/{product}/print', [ProductController::class, 'printQRCodes'])->name('products.printQRCodes');
    Route::get('/products/{id}/download-qrcodes', [ProductController::class, 'downloadQRCodes'])->name('products.downloadQRCodes');

    // Units Products
    Route::delete('/unit-products/{productUnit}', [ProductUnitController::class, 'destroy'])->name(
        'unit.product.delete'
    );

    // Sell
    Route::get('confirm/{serialNumber}/sell', [SaleController::class, 'confirm'])->name('confirm');
    Route::get('product/{serialNumber}/sell', [SaleController::class, 'sell'])->name('sell');
    Route::post('product/{serialNumber}/cancel-sale', [SaleController::class, 'cancel'])->name('cancel');
});

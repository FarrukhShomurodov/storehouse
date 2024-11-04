<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('login', [AuthController::class, 'showLoginForm']);
Route::post('login', [AuthController::class, 'login'])->name('login');


Route::group(['middleware' => 'auth'], function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Products
    Route::resource('products', ProductController::class);
    Route::get('/products/{product}/confirm-sale', [ProductController::class, 'confirmSale'])->name(
        'products.confirmSale'
    );
    Route::get('/products/{product}/confirmed', [ProductController::class, 'sale'])->name('products.sale');

    Route::get('/products/{product}/redirect', [ProductController::class, 'redirect']);

    Route::get('/products/{product}/download-qr', [ProductController::class, 'downloadQrCode'])->name(
        'products.downloadQrCode'
    );
});


Route::get('test', function () {
    $product = \App\Models\Product::query()->first();
    return view('admin.products.sailed', [
        'product' => $product,
        'message' => 'Товар успешно продан!',
    ]);
});

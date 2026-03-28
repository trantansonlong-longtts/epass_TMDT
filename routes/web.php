<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminHomepageContentController;
use App\Http\Controllers\AdminProductController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index']);
Route::get('/mua-ngay/{id}', [OrderController::class, 'checkout'])->name('checkout');
Route::post('/xu-ly-dat-hang/{id}', [OrderController::class, 'processCheckout'])->name('process.checkout');
Route::post('/api/quet-cavet', [OrderController::class, 'scanCavet'])->name('api.scan_cavet');
Route::get('/thanh-toan-qr/{id}', [OrderController::class, 'paymentQR'])->name('payment.qr');
Route::post('/xac-nhan-chuyen-khoan/{id}', [OrderController::class, 'confirmPayment'])->name('payment.confirm');
Route::get('/tra-cuu', [OrderController::class, 'trackOrder'])->name('order.track');

Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminController::class, 'login'])->name('admin.login.submit');

    Route::middleware('admin.auth')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
        Route::get('/content/homepage', [AdminHomepageContentController::class, 'edit'])->name('admin.content.homepage.edit');
        Route::post('/content/homepage', [AdminHomepageContentController::class, 'update'])->name('admin.content.homepage.update');
        Route::post('/order/{id}/update-status', [AdminController::class, 'updateStatus'])->name('admin.order.updateStatus');
        Route::post('/order/{id}/update-details', [AdminController::class, 'updateDetails'])->name('admin.order.updateDetails');
        Route::get('/products', [AdminProductController::class, 'index'])->name('admin.products.index');
        Route::get('/products/create', [AdminProductController::class, 'create'])->name('admin.products.create');
        Route::post('/products', [AdminProductController::class, 'store'])->name('admin.products.store');
        Route::post('/products/reorder', [AdminProductController::class, 'reorder'])->name('admin.products.reorder');
        Route::get('/products/{product}/edit', [AdminProductController::class, 'edit'])->name('admin.products.edit');
        Route::put('/products/{product}', [AdminProductController::class, 'update'])->name('admin.products.update');
        Route::patch('/products/{product}/toggle-visibility', [AdminProductController::class, 'toggleVisibility'])->name('admin.products.toggleVisibility');
        Route::delete('/products/{product}', [AdminProductController::class, 'destroy'])->name('admin.products.destroy');
        Route::post('/logout', [AdminController::class, 'logout'])->name('admin.logout');
    });
});

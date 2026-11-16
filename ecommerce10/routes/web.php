<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('products/{product}', [HomeController::class, 'show'])->name('products.show.public');
Route::get('search', [HomeController::class, 'search'])->name('search');
Route::get('products', fn() => 'Products Page');
Route::get('cart', [CartController::class, 'index'])->name('cart');
Route::post('cart/items', [CartController::class, 'store'])->name('cart.items.store');
Route::patch('cart/items/{product}', [CartController::class, 'update'])->name('cart.items.update');
Route::delete('cart/items/{product}', [CartController::class, 'destroy'])->name('cart.items.destroy');
Route::get('checkout', [OrderController::class, 'create'])->name('checkout');
Route::post('checkout', [OrderController::class, 'store'])->name('checkout.store');
Route::get('invoice', [OrderController::class, 'invoice'])->name('invoice');

Route::middleware('auth')->group(function () {
    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
});

Route::prefix('dashboard')->middleware(['admin'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('products', \App\Http\Controllers\ProductController::class);
    Route::resource('product-categories', \App\Http\Controllers\ProductCategoryController::class)->except(['show', 'create', 'edit']);
    Route::get('orders', [\App\Http\Controllers\Admin\OrderController::class, 'index'])->name('admin.orders.index');
    Route::get('orders/{order}', [\App\Http\Controllers\Admin\OrderController::class, 'show'])->name('admin.orders.show');
    Route::patch('orders/{order}/status', [\App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('admin.orders.update-status');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

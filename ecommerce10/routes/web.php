<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('search', [HomeController::class, 'search'])->name('search');
Route::get('products', fn() => 'Products Page');
Route::get('cart', [CartController::class, 'index'])->name('cart');
Route::post('cart/items', [CartController::class, 'store'])->name('cart.items.store');
Route::patch('cart/items/{product}', [CartController::class, 'update'])->name('cart.items.update');
Route::delete('cart/items/{product}', [CartController::class, 'destroy'])->name('cart.items.destroy');
Route::get('checkout', [OrderController::class, 'create'])->name('checkout');
Route::post('checkout', [OrderController::class, 'store'])->name('checkout.store');
Route::get('invoice', [OrderController::class, 'invoice'])->name('invoice');

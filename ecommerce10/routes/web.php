<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('products', fn () => 'Products Page');
Route::get('cart', [CartController::class, 'index'])->name('cart');
Route::get('checkout', fn () => 'Checkout Page');

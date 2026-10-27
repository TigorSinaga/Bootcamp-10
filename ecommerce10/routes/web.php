<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;

// Route::get('/', function () {
//     return view('welcome');
// });

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

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

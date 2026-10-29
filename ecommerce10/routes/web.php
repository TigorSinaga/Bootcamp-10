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

Route::prefix('dashboard')->middleware(['auth'])->group(function () {
    Route::get('/', function () {
        return view('dashboard', [
            'productCount' => \App\Models\Product::count(),
            'categoryCount' => \App\Models\ProductCategory::count(),
            'orderCount' => \App\Models\Order::count(),
            'totalRevenue' => \App\Models\Order::where('status', '!=', 'cancelled')->sum('total_amount'),
            'lowStockCount' => \App\Models\Product::where('stock', '<=', 10)->count(),
            'latestProducts' => \App\Models\Product::with('product_category')->latest()->take(5)->get(),
            'latestOrders' => \App\Models\Order::with('user')->latest()->take(5)->get(),
        ]);
    })->name('dashboard');
    Route::resource('products', \App\Http\Controllers\ProductController::class);
    Route::resource('product-categories', \App\Http\Controllers\ProductCategoryController::class)->except(['show', 'create', 'edit']);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

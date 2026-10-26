<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductCategory;

class HomeController extends Controller
{
    public function index(): View
    {
        $products = Product::where('stock', '>', 0)
            ->orderBy('price', 'desc')
            ->with('product_category')
            ->paginate(12);



        $categories = [
            ['slug' => 'fashion', 'name' => 'Fashion', 'icon' => '👕', 'style' => 'cat-fashion', 'product_count' => '120+'],
            ['slug' => 'elektronik', 'name' => 'Elektronik', 'icon' => '🎧', 'style' => 'cat-tech', 'product_count' => '86+'],
            ['slug' => 'rumah', 'name' => 'Rumah & Living', 'icon' => '🪴', 'style' => 'cat-home', 'product_count' => '94+'],
            ['slug' => 'kecantikan', 'name' => 'Kecantikan', 'icon' => '🧴', 'style' => 'cat-beauty', 'product_count' => '68+'],
            ['slug' => 'olahraga', 'name' => 'Olahraga', 'icon' => '🏃', 'style' => 'cat-sports', 'product_count' => '52+'],
            ['slug' => 'makanan', 'name' => 'Makanan', 'icon' => '☕', 'style' => 'cat-food', 'product_count' => '73+'],
        ];

        $categories = ProductCategory::all();
        return view(
            'home.index',
            compact('products', 'categories')
        );
    }

    public function search(Request $request): View
    {
        $search = trim((string) $request->input('search', ''));
        $category = $request->input('category', '');
        $query = Product::query()
            ->where('stock', '>', 0)
            ->with('product_category');

        if ($search !== '') {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('product_category', function ($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if ($category !== '') {
            $query->where('product_category_id', $category);
        }

        $categories = ProductCategory::all();
        $products = $query->orderBy('name')->paginate(12)->withQueryString();

        return view(
            'home.search',
            compact('products', 'categories', 'search', 'category')
        );
    }
}

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



        $categories = ProductCategory::withCount('products')->get();
        return view(
            'home.index',
            compact('products', 'categories')
        );
    }

    public function search(Request $request): View
    {
        $search = trim((string) $request->input('search', ''));
        $categoryInput = $request->input('category', []);
        $request->merge([
            'category' => is_array($categoryInput)
                ? $categoryInput
                : ($categoryInput === '' ? [] : [$categoryInput]),
        ]);
        $filters = $request->validate([
            'category' => ['array'],
            'category.*' => ['integer', 'exists:product_categories,id'],
            'min_price' => ['nullable', 'numeric', 'min:0'],
            'max_price' => ['nullable', 'numeric', 'min:0'],
            'sort' => ['nullable', 'in:latest,price_desc,price_asc'],
        ]);
        $category = $filters['category'] ?? [];
        $minPrice = $filters['min_price'] ?? '';
        $maxPrice = $filters['max_price'] ?? '';
        $sort = $filters['sort'] ?? 'latest';
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

        if ($category !== []) {
            $query->whereIn('product_category_id', $category);
        }

        $categories = ProductCategory::all();
        if ($minPrice !== '') {
            $query->where('price', '>=', $minPrice);
        }

        if ($maxPrice !== '') {
            $query->where('price', '<=', $maxPrice);
        }

        match ($sort) {
            'price_desc' => $query->orderByDesc('price'),
            'price_asc' => $query->orderBy('price'),
            default => $query->latest(),
        };

        $products = $query->paginate(12)->withQueryString();

        return view(
            'home.search',
            compact('products', 'categories', 'search', 'category', 'minPrice', 'maxPrice', 'sort')
        );
    }
}

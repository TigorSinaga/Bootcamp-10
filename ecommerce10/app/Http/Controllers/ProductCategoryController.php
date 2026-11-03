<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = ProductCategory::withCount('products')
            ->withSum('products', 'stock')
            ->orderBy('name')
            ->get();
        return view('admin.product_categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */

    public function create()
    {
        return redirect()->route('product-categories.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate(['name' => ['required', 'string', 'max:255', 'unique:product_categories,name']]);
        $name_check = ProductCategory::where('name', $validated['name'])->exists();
        if ($name_check) {
            return redirect()->route('product-categories.index')->with('error', 'Kategori sudah ada.');
        }
        ProductCategory::create(['name' => $validated['name'], 'slug' => Str::slug($validated['name'])]);
        return redirect()->route('product-categories.index')->with('success', 'Kategori berhasil ditambahkan.');
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductCategory $productCategory)
    {
        return redirect()->route('product-categories.index');
    }

    public function update(Request $request, ProductCategory $productCategory)
    {
        $validated = $request->validate(['name' => ['required', 'string', 'max:255', 'unique:product_categories,name,' . $productCategory->id]]);
        $productCategory->update(['name' => $validated['name'], 'slug' => Str::slug($validated['name'])]);
        return redirect()->route('product-categories.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(ProductCategory $productCategory)
    {
        DB::transaction(function () use ($productCategory) {
            $productCategory->products()->update(['product_category_id' => null]);
            $productCategory->delete();
        });

        return redirect()->route('product-categories.index')
            ->with('success', 'Kategori berhasil dihapus. Produk terkait sekarang tidak memiliki kategori.');
    }
}

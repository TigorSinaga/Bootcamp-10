<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;

class CartController extends Controller
{
    public function index(): View
    {
        $cart = session('cart', []);
        $products = Product::with('product_category')
            ->whereIn('id', array_keys($cart))
            ->where('stock', '>', 0)
            ->get()
            ->keyBy('id');
        $items = [];
        $validCart = [];

        foreach ($cart as $productId => $quantity) {
            $product = $products->get($productId);
            if (!$product) {
                continue;
            }

            $quantity = min((int) $quantity, $product->stock);
            $validCart[$productId] = $quantity;
            $items[] = [
                'product' => $product,
                'quantity' => $quantity,
            ];
        }

        if ($validCart !== $cart) {
            session(['cart' => $validCart]);
        }

        $cartCount = array_sum($validCart);
        $subtotal = array_sum(array_map(
            fn (array $item) => (float) $item['product']->price * $item['quantity'],
            $items,
        ));
        $shippingFee = $subtotal >= 100000 ? 0 : 12000;

        return view('cart.index', [
            'items' => $items,
            'cartCount' => $cartCount,
            'subtotal' => $subtotal,
            'shippingFee' => $shippingFee,
            'total' => $subtotal + $shippingFee,
        ]);
    }

    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
        ]);
        $product = Product::findOrFail($validated['product_id']);
        $cart = session('cart', []);
        $quantity = ($cart[$product->id] ?? 0) + 1;

        if ($product->stock < 1 || $quantity > $product->stock) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Stok produk tidak mencukupi.',
                ], 422);
            }

            return back()->with('error', 'Stok produk tidak mencukupi.');
        }

        $cart[$product->id] = $quantity;
        session(['cart' => $cart]);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => "{$product->name} ditambahkan ke keranjang.",
                'cartCount' => array_sum($cart),
            ]);
        }

        return back()->with('success', "{$product->name} ditambahkan ke keranjang.");
    }

    public function update(Request $request, Product $product): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        if ($validated['quantity'] > $product->stock) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Jumlah melebihi stok produk yang tersedia.',
                ], 422);
            }

            return back()->with('error', 'Jumlah melebihi stok produk yang tersedia.');
        }

        $cart = session('cart', []);
        if (array_key_exists($product->id, $cart)) {
            $cart[$product->id] = (int) $validated['quantity'];
            session(['cart' => $cart]);
        }

        if ($request->expectsJson()) {
            $products = Product::whereIn('id', array_keys($cart))
                ->where('stock', '>', 0)
                ->get()
                ->keyBy('id');
            $subtotal = 0;
            foreach ($cart as $productId => $quantity) {
                $cartProduct = $products->get($productId);
                if ($cartProduct) {
                    $subtotal += (float) $cartProduct->price * (int) $quantity;
                }
            }
            $shippingFee = $subtotal >= 100000 ? 0 : 12000;

            return response()->json([
                'message' => 'Jumlah produk berhasil diperbarui.',
                'productId' => $product->id,
                'quantity' => $cart[$product->id] ?? 0,
                'itemTotal' => (float) $product->price * (int) ($cart[$product->id] ?? 0),
                'cartCount' => array_sum($cart),
                'subtotal' => $subtotal,
                'shippingFee' => $shippingFee,
                'total' => $subtotal + $shippingFee,
                'shippingMessage' => $shippingFee === 0
                    ? 'Yeay, kamu mendapatkan gratis ongkir!'
                    : 'Belanja lagi Rp'.number_format(max(0, 100000 - $subtotal), 0, ',', '.').' untuk gratis ongkir.',
            ]);
        }

        return back();
    }

    public function destroy(Product $product): RedirectResponse
    {
        $cart = session('cart', []);
        unset($cart[$product->id]);
        session(['cart' => $cart]);

        return back();
    }
}

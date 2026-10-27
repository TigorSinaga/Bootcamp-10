<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('checkout.index', $this->cartDetails());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['required', 'email', 'max:255'],
            'address' => ['required', 'string', 'max:2000'],
            'delivery_note' => ['nullable', 'string', 'max:1000'],
            'payment_method' => ['required', 'in:bank_transfer,ewallet,minimarket'],
        ]);
        $cart = $this->cartDetails();

        if ($cart['items'] === []) {
            return redirect()->route('checkout')
                ->with('error', 'Keranjang kosong. Tambahkan produk sebelum membuat invoice.');
        }

        foreach ($cart['items'] as $item) {
            if ($item['quantity'] > $item['product']->stock) {
                return redirect()->route('cart')
                    ->with('error', "Stok {$item['product']->name} tidak mencukupi.");
            }
        }

        $paymentMethods = [
            'bank_transfer' => 'Transfer bank',
            'ewallet' => 'E-wallet',
            'minimarket' => 'Minimarket',
        ];
        $invoiceItems = array_map(
            fn (array $item) => [
                'name' => $item['product']->name,
                'quantity' => $item['quantity'],
                'price' => (float) $item['product']->price,
                'total' => (float) $item['product']->price * $item['quantity'],
            ],
            $cart['items'],
        );
        $invoice = [
            'id' => random_int(1000000000, 9999999999),
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'address' => $validated['address'],
            'delivery_note' => $validated['delivery_note'] ?? '',
            'payment_method' => $paymentMethods[$validated['payment_method']],
            'items' => $invoiceItems,
            'cart_count' => $cart['cartCount'],
            'subtotal' => $cart['subtotal'],
            'shipping_fee' => $cart['shippingFee'],
            'total' => $cart['total'],
            'created_at' => now()->format('d/m/Y H:i'),
        ];

        session([
            'pending_invoice' => $invoice,
            'cart' => [],
        ]);

        return redirect()->route('invoice');
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        //
    }

    public function invoice(): View|RedirectResponse
    {
        $invoice = session('pending_invoice');

        if (!$invoice) {
            return redirect()->route('checkout')
                ->with('error', 'Invoice tidak ditemukan. Silakan lengkapi checkout terlebih dahulu.');
        }

        return view('checkout.invoice', compact('invoice'));
    }

    private function cartDetails(): array
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

        return [
            'items' => $items,
            'cartCount' => $cartCount,
            'subtotal' => $subtotal,
            'shippingFee' => $shippingFee,
            'total' => $subtotal + $shippingFee,
        ];
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
}

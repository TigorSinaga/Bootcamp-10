<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display a listing of the customer's orders.
     */
    public function index(): View
    {
        $orders = Order::where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource (checkout).
     */
    public function create(): View
    {
        return view('checkout.index', $this->cartDetails());
    }

    /**
     * Store a newly created resource in storage (order submission).
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

        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->route('checkout')
                ->with('error', 'Keranjang kosong. Tambahkan produk sebelum membuat pesanan.');
        }

        try {
            $order = DB::transaction(function () use ($validated, $cart) {
                $productIds = array_keys($cart);
                $products = Product::whereIn('id', $productIds)
                    ->lockForUpdate()
                    ->get()
                    ->keyBy('id');

                $items = [];
                $subtotal = 0;

                foreach ($cart as $productId => $quantity) {
                    $product = $products->get($productId);

                    if (!$product || !$product->is_active) {
                        throw new \Exception("Produk tidak ditemukan atau sudah tidak aktif.");
                    }

                    if ($quantity > $product->stock) {
                        throw new \Exception("Stok untuk produk {$product->name} tidak mencukupi (tersisa {$product->stock}).");
                    }

                    $itemPrice = (float) $product->price;
                    $itemSubtotal = $itemPrice * (int) $quantity;
                    $subtotal += $itemSubtotal;

                    $items[] = [
                        'product' => $product,
                        'quantity' => (int) $quantity,
                        'price' => $itemPrice,
                        'subtotal' => $itemSubtotal,
                    ];
                }

                $shippingFee = $subtotal >= 100000 ? 0 : 12000;
                $totalAmount = $subtotal + $shippingFee;

                $orderNumber = 'ORD-' . date('Ymd') . '-' . random_int(1000, 9999);
                while (Order::where('order_number', $orderNumber)->exists()) {
                    $orderNumber = 'ORD-' . date('Ymd') . '-' . random_int(1000, 9999);
                }

                $order = Order::create([
                    'order_number' => $orderNumber,
                    'user_id' => Auth::id(),
                    'customer_name' => $validated['name'],
                    'customer_email' => $validated['email'],
                    'customer_phone' => $validated['phone'],
                    'shipping_address' => $validated['address'],
                    'subtotal' => $subtotal,
                    'shipping_fee' => $shippingFee,
                    'total_amount' => $totalAmount,
                    'status' => 'pending',
                    'payment_status' => 'unpaid',
                    'payment_method' => $validated['payment_method'],
                    'notes' => $validated['delivery_note'] ?? null,
                ]);

                foreach ($items as $item) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item['product']->id,
                        'product_name' => $item['product']->name,
                        'price' => $item['price'],
                        'quantity' => $item['quantity'],
                        'subtotal' => $item['subtotal'],
                    ]);

                    $item['product']->decrement('stock', $item['quantity']);
                }

                return $order;
            });

            session()->forget('cart');
            session(['last_order_id' => $order->id]);

            return redirect()->route('orders.show', $order)
                ->with('success', 'Pesanan berhasil dibuat! Terima kasih sudah berbelanja.');

        } catch (\Exception $e) {
            return redirect()->route('checkout')
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified customer order.
     */
    public function show(Order $order): View|RedirectResponse
    {
        if (Auth::check() && Auth::id() !== $order->user_id && Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $order->load('order_items.product');

        return view('orders.show', compact('order'));
    }

    public function invoice(): View|RedirectResponse
    {
        $orderId = session('last_order_id');
        $order = $orderId ? Order::with('order_items.product')->find($orderId) : null;

        if (!$order) {
            $invoice = session('pending_invoice');
            if (!$invoice) {
                return redirect()->route('checkout')
                    ->with('error', 'Invoice tidak ditemukan. Silakan lengkapi checkout terlebih dahulu.');
            }
            return view('checkout.invoice', compact('invoice'));
        }

        return view('orders.show', compact('order'));
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
}

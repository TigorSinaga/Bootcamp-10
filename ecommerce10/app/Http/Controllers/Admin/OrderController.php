<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of orders for admin.
     */
    public function index(Request $request): View
    {
        $query = Order::with('user');

        // Search by order number or customer name/email
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($status = $request->input('status')) {
            if ($status !== 'all') {
                $query->where('status', $status);
            }
        }

        // Filter by date
        if ($date = $request->input('date')) {
            $query->whereDate('created_at', $date);
        }

        // Sorting
        $sort = $request->input('sort', 'latest');
        if ($sort === 'oldest') {
            $query->oldest();
        } elseif ($sort === 'highest') {
            $query->orderByDesc('total_amount');
        } elseif ($sort === 'lowest') {
            $query->orderBy('total_amount');
        } else {
            $query->latest();
        }

        $orders = $query->paginate(10)->withQueryString();

        $statusCounts = [
            'all' => Order::count(),
            'pending' => Order::where('status', 'pending')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'shipped' => Order::where('status', 'shipped')->count(),
            'completed' => Order::where('status', 'completed')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
        ];

        return view('admin.orders.index', compact('orders', 'statusCounts'));
    }

    /**
     * Display the specified order detail for admin.
     */
    public function show(Order $order): View
    {
        $order->load('user', 'order_items.product');

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update order status.
     */
    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,processing,shipped,completed,cancelled'],
            'payment_status' => ['nullable', 'in:unpaid,paid,refunded'],
        ]);

        DB::transaction(function () use ($validated, $order) {
            $oldStatus = $order->status;
            $newStatus = $validated['status'];

            // If order is cancelled from non-cancelled, restore stock
            if ($newStatus === 'cancelled' && $oldStatus !== 'cancelled') {
                foreach ($order->order_items as $item) {
                    if ($item->product) {
                        $item->product->increment('stock', $item->quantity);
                    }
                }
            }

            // If order was cancelled and now reactivated (non-cancelled), check & deduct stock if possible
            if ($oldStatus === 'cancelled' && $newStatus !== 'cancelled') {
                foreach ($order->order_items as $item) {
                    if ($item->product) {
                        if ($item->product->stock < $item->quantity) {
                            throw new \Exception("Stok produk {$item->product->name} tidak mencukupi untuk mengaktifkan kembali pesanan ini.");
                        }
                        $item->product->decrement('stock', $item->quantity);
                    }
                }
            }

            $order->update([
                'status' => $newStatus,
                'payment_status' => $validated['payment_status'] ?? $order->payment_status,
            ]);
        });

        return back()->with('success', "Status pesanan #{$order->order_number} berhasil diperbarui.");
    }
}

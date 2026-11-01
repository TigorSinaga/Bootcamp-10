<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $productCount  = Product::count();
        $categoryCount = ProductCategory::count();
        $userCount     = User::count();
        $orderCount    = Order::count();
        $totalRevenue  = Order::where('status', '!=', 'cancelled')->sum('total_amount');
        $lowStockCount = Product::where('stock', '<=', 10)->count();

        // Orders per month (last 6 months) — MySQL compatible
        $ordersPerMonth = Order::select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
                DB::raw('COUNT(*) as total')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Build full 6-month labels + data (fill gaps with 0)
        $monthLabels  = [];
        $monthData    = [];
        $monthMap     = $ordersPerMonth->keyBy('month');
        for ($i = 5; $i >= 0; $i--) {
            $key           = now()->subMonths($i)->format('Y-m');
            $label         = now()->subMonths($i)->translatedFormat('M Y');
            $monthLabels[] = $label;
            $monthData[]   = $monthMap->has($key) ? (int) $monthMap[$key]->total : 0;
        }

        // Order status distribution
        $statusCounts = Order::select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $allStatuses  = ['pending', 'processing', 'completed', 'cancelled'];
        $statusLabels = ['Pending', 'Processing', 'Completed', 'Cancelled'];
        $statusData   = array_map(fn($s) => $statusCounts[$s] ?? 0, $allStatuses);

        // Use dummy data when DB is empty
        if ($orderCount === 0) {
            $monthLabels = ['Jun 2026','Jul 2026','Aug 2026','Sep 2026','Oct 2026','Nov 2026'];
            $monthData   = [12, 19, 8, 25, 17, 30];
            $statusData  = [8, 5, 12, 3];
        }

        $latestProducts = Product::with('product_category')->latest()->take(5)->get();
        $latestOrders   = Order::with('user')->latest()->take(5)->get();

        // Product clicks dummy (no real tracking yet)
        $productClicks = 4821;

        return view('admin.dashboard', compact(
            'productCount', 'categoryCount', 'userCount', 'orderCount',
            'totalRevenue', 'lowStockCount', 'productClicks',
            'monthLabels', 'monthData',
            'statusLabels', 'statusData',
            'latestProducts', 'latestOrders'
        ));
    }
}

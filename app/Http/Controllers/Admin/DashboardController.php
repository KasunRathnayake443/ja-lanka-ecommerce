<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Get current date ranges
        $today = now()->startOfDay();
        $thisMonth = now()->startOfMonth();
        $lastMonth = now()->subMonth()->startOfMonth();
        
        // Statistics
        $totalProducts = Product::count();
        $totalOrders = Order::count();
        $totalCustomers = User::count();
        $totalCategories = Category::count();
        $totalBrands = Brand::count();
        $totalCoupons = Coupon::count();
        
        // Today's stats
        $todayOrders = Order::whereDate('created_at', $today)->count();
        $todaySales = Order::whereDate('created_at', $today)->sum('grand_total');
        $todayCustomers = User::whereDate('created_at', $today)->count();
        
        // Monthly stats
        $thisMonthOrders = Order::where('created_at', '>=', $thisMonth)->count();
        $thisMonthSales = Order::where('created_at', '>=', $thisMonth)->sum('grand_total');
        $thisMonthCustomers = User::where('created_at', '>=', $thisMonth)->count();
        
        // Compare with last month
        $lastMonthOrders = Order::whereBetween('created_at', [$lastMonth, $thisMonth])->count();
        $ordersGrowth = $lastMonthOrders > 0 
            ? round((($thisMonthOrders - $lastMonthOrders) / $lastMonthOrders) * 100, 1)
            : ($thisMonthOrders > 0 ? 100 : 0);
        
        // Order status breakdown
        $orderStatuses = [
            'pending' => Order::where('order_status', 'pending')->count(),
            'processing' => Order::where('order_status', 'processing')->count(),
            'shipped' => Order::where('order_status', 'shipped')->count(),
            'delivered' => Order::where('order_status', 'delivered')->count(),
            'cancelled' => Order::where('order_status', 'cancelled')->count(),
        ];
        
        // Payment status breakdown
        $paymentStatuses = [
            'pending' => Order::where('payment_status', 'pending')->count(),
            'paid' => Order::where('payment_status', 'paid')->sum('grand_total'),
            'failed' => Order::where('payment_status', 'failed')->count(),
            'refunded' => Order::where('payment_status', 'refunded')->count(),
        ];
        
        // Recent orders
        $recentOrders = Order::with('user')
            ->latest()
            ->take(10)
            ->get();
        
        // Low stock products
        $lowStockProducts = Product::whereHas('inventory', function($q) {
            $q->where('quantity_on_hand', '<=', DB::raw('reorder_level'));
        })->with('inventory')->take(10)->get();
        
        // Top selling products (last 30 days)
        $topProducts = Product::select('products.id', 'products.name', 'products.sku')
            ->selectRaw('SUM(order_items.quantity) as total_sold')
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.created_at', '>=', now()->subDays(30))
            ->groupBy('products.id', 'products.name', 'products.sku')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();
        
        // Weekly sales data for chart (last 7 days)
        $weeklySales = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $sales = Order::whereDate('created_at', $date)->sum('grand_total');
            $weeklySales[] = [
                'day' => $date->format('D'),
                'sales' => $sales,
                'orders' => Order::whereDate('created_at', $date)->count(),
            ];
        }
        
        return view('admin.dashboard', compact(
            'totalProducts', 'totalOrders', 'totalCustomers', 'totalCategories', 'totalBrands', 'totalCoupons',
            'todayOrders', 'todaySales', 'todayCustomers',
            'thisMonthOrders', 'thisMonthSales', 'thisMonthCustomers', 'ordersGrowth',
            'orderStatuses', 'paymentStatuses',
            'recentOrders', 'lowStockProducts', 'topProducts', 'weeklySales'
        ));
    }
}
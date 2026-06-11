<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'shippingAddress']);

        // Search by order number
        if ($request->has('search') && $request->search != '') {
            $query->where('order_number', 'like', "%{$request->search}%");
        }

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('order_status', $request->status);
        }

        // Filter by payment status
        if ($request->has('payment_status') && $request->payment_status != '') {
            $query->where('payment_status', $request->payment_status);
        }

        // Filter by date range
        if ($request->has('date_from') && $request->date_from != '') {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to != '') {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->latest()->paginate(20);

        // Get statistics
        $totalOrders = Order::count();
        $pendingOrders = Order::where('order_status', 'pending')->count();
        $processingOrders = Order::where('order_status', 'processing')->count();
        $shippedOrders = Order::where('order_status', 'shipped')->count();
        $deliveredOrders = Order::where('order_status', 'delivered')->count();
        $cancelledOrders = Order::where('order_status', 'cancelled')->count();
        $totalRevenue = Order::where('payment_status', 'paid')->sum('grand_total');

        return view('admin.orders.index', compact(
            'orders', 'totalOrders', 'pendingOrders', 'processingOrders',
            'shippedOrders', 'deliveredOrders', 'cancelledOrders', 'totalRevenue'
        ));
    }

    public function show($id)
    {
        $order = Order::with(['user', 'shippingAddress', 'items.product'])
            ->findOrFail($id);
        
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'order_status' => 'required|in:pending,processing,shipped,delivered,cancelled,refunded',
            'tracking_number' => 'nullable|string|max:255'
        ]);

        $order = Order::findOrFail($id);
        $oldStatus = $order->order_status;
        $order->update([
            'order_status' => $request->order_status,
            'tracking_number' => $request->tracking_number
        ]);

        // If order is cancelled, restore inventory
        if ($request->order_status === 'cancelled' && $oldStatus !== 'cancelled') {
            foreach ($order->items as $item) {
                $inventory = Inventory::where('product_id', $item->product_id)->first();
                if ($inventory) {
                    $inventory->increment('quantity_on_hand', $item->quantity);
                    $inventory->decrement('quantity_sold', $item->quantity);
                }
            }
        }

        return redirect()->route('admin.orders.show', $order->id)
            ->with('success', 'Order status updated successfully!');
    }

    public function updatePaymentStatus(Request $request, $id)
    {
        $request->validate([
            'payment_status' => 'required|in:pending,paid,failed,refunded'
        ]);

        $order = Order::findOrFail($id);
        $order->update(['payment_status' => $request->payment_status]);

        return redirect()->route('admin.orders.show', $order->id)
            ->with('success', 'Payment status updated successfully!');
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        
        // Restore inventory before deleting
        foreach ($order->items as $item) {
            $inventory = Inventory::where('product_id', $item->product_id)->first();
            if ($inventory) {
                $inventory->increment('quantity_on_hand', $item->quantity);
                $inventory->decrement('quantity_sold', $item->quantity);
            }
        }
        
        $order->delete();

        return redirect()->route('admin.orders.index')
            ->with('success', 'Order deleted successfully!');
    }

    public function invoice($id)
    {
        $order = Order::with(['user', 'shippingAddress', 'items.product'])
            ->findOrFail($id);
        
        return view('admin.orders.invoice', compact('order'));
    }
}
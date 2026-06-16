<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Inventory;
use App\Models\Address;
use App\Models\Coupon;
use App\Models\Product;
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

        /**
     * NEW: Show manual order creation form
     */
    public function createManual()
    {
        $users = User::where('is_active', true)->orderBy('name')->get();
        return view('admin.orders.create-manual', compact('users'));
    }

    /**
     * NEW: Search products for manual order (AJAX)
     */
    public function searchProducts(Request $request)
    {
        $request->validate([
            'search' => 'required|string|min:2'
        ]);

        $search = $request->search;
        
        $products = Product::with(['inventory', 'images' => function($q) {
            $q->where('is_main', true);
        }])
        ->where('is_active', true)
        ->where('is_available', true)
        ->where(function($query) use ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
        })
        ->limit(10)
        ->get()
        ->map(function($product) {
            $currentPrice = $product->current_price;
            $stock = $product->inventory ? $product->inventory->quantity_on_hand : 0;
            
            return [
                'id' => $product->id,
                'name' => $product->name,
                'sku' => $product->sku,
                'regular_price' => $product->regular_price,
                'sale_price' => $product->sale_price,
                'current_price' => $currentPrice,
                'stock' => $stock,
                'image' => $product->images->first() ? asset('storage/' . $product->images->first()->image_path) : null,
                'has_sale' => $product->hasActiveSale(),
                'discount_percent' => $product->discount_percent
            ];
        });

        return response()->json($products);
    }

    /**
     * NEW: Get user details with addresses for auto-fill (AJAX)
     */
    public function getUserDetails($id)
    {
        $user = User::with(['addresses' => function($q) {
            $q->orderBy('is_default', 'desc')->latest();
        }])->findOrFail($id);

        $defaultAddress = $user->addresses->where('is_default', true)->first() ?? $user->addresses->first();
        
        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'mobile' => $user->mobile,
            'addresses' => $user->addresses->map(function($address) {
                return [
                    'id' => $address->id,
                    'label' => $address->label,
                    'full_name' => $address->full_name,
                    'mobile' => $address->mobile,
                    'address_line1' => $address->address_line1,
                    'address_line2' => $address->address_line2,
                    'city' => $address->city,
                    'district' => $address->district,
                    'province' => $address->province,
                    'postal_code' => $address->postal_code,
                    'delivery_instructions' => $address->delivery_instructions,
                    'full_address' => $address->full_address,
                    'is_default' => $address->is_default
                ];
            }),
            'default_address' => $defaultAddress ? [
                'id' => $defaultAddress->id,
                'full_name' => $defaultAddress->full_name,
                'mobile' => $defaultAddress->mobile,
                'address_line1' => $defaultAddress->address_line1,
                'address_line2' => $defaultAddress->address_line2,
                'city' => $defaultAddress->city,
                'district' => $defaultAddress->district,
                'province' => $defaultAddress->province,
                'postal_code' => $defaultAddress->postal_code,
                'delivery_instructions' => $defaultAddress->delivery_instructions,
                'full_address' => $defaultAddress->full_address
            ] : null
        ]);
    }

    /**
     * NEW: Validate coupon for manual order (AJAX)
     */
    public function validateCoupon(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'subtotal' => 'required|numeric|min:0'
        ]);
        
        $couponCode = strtoupper($request->code);
        $subtotal = $request->subtotal;
        
        $coupon = Coupon::where('code', $couponCode)->first();
        
        if (!$coupon) {
            return response()->json([
                'valid' => false,
                'message' => 'Invalid coupon code'
            ]);
        }
        
        // Check if coupon is valid using the model's method
        if (!$coupon->isValid($subtotal)) {
            return response()->json([
                'valid' => false,
                'message' => 'Coupon is not valid or has expired'
            ]);
        }
        
        $discountAmount = $coupon->calculateDiscount($subtotal);
        
        return response()->json([
            'valid' => true,
            'coupon' => [
                'id' => $coupon->id,
                'code' => $coupon->code,
                'discount_type' => $coupon->discount_type,
                'discount_value' => $coupon->discount_value
            ],
            'discount_amount' => $discountAmount
        ]);
    }

    /**
     * NEW: Store manual order from admin panel
     */
    public function storeManual(Request $request)
    {
        dd($request->all());
        $request->validate([
            'user_type' => 'required|in:existing,guest',
            'user_id' => 'required_if:user_type,existing|exists:users,id',
            'guest_name' => 'required_if:user_type,guest|string|max:255',
            'guest_email' => 'required_if:user_type,guest|email|max:255',
            'guest_mobile' => 'required_if:user_type,guest|string|max:20',
            'address_id' => 'nullable|exists:addresses,id',
            'full_name' => 'required|string|max:255',
            'mobile' => 'required|string|max:20',
            'address_line1' => 'required|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'district' => 'required|string|max:100',
            'province' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'delivery_instructions' => 'nullable|string',
            'shipping_method' => 'required|in:standard,express,pickup',
            'payment_method' => 'required|in:card,cod,bank_transfer',
            'payment_status' => 'required|in:pending,paid,failed',
            'order_status' => 'required|in:pending,processing,shipped,delivered',
            'notes' => 'nullable|string',
            'items' => 'required|string',
            'coupon_code' => 'nullable|string',
            'manual_discount' => 'nullable|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            // Decode items from JSON
            $items = json_decode($request->items, true);
            
            if (empty($items)) {
                throw new \Exception('No items in order');
            }

            // Calculate subtotal
            $subtotal = 0;
            foreach ($items as $item) {
                $subtotal += $item['quantity'] * $item['unit_price'];
            }

            // Calculate shipping
            $shippingAmount = 0;
            if ($request->shipping_method === 'express') {
                $shippingAmount = 650;
            } elseif ($request->shipping_method === 'standard') {
                $shippingAmount = 350;
            }
            
            // Calculate coupon discount
            $couponDiscount = 0;
            $coupon = null;
            $appliedCouponCode = null;
            
            if ($request->coupon_code) {
                $coupon = Coupon::where('code', strtoupper($request->coupon_code))->first();
                if ($coupon && $coupon->isValid($subtotal)) {
                    $couponDiscount = $coupon->calculateDiscount($subtotal);
                    $appliedCouponCode = $coupon->code;
                }
            }
            
            // Manual discount
            $manualDiscount = $request->manual_discount ?? 0;
            
            // Total discount
            $totalDiscount = $couponDiscount + $manualDiscount;
            
            // Calculate tax (5% on amount after discount)
            $taxableAmount = $subtotal - $totalDiscount;
            $taxAmount = $taxableAmount * 0.05;
            
            // Grand total
            $grandTotal = $taxableAmount + $shippingAmount + $taxAmount;
            
            // Create or use existing address
            $address = null;
            if ($request->address_id) {
                $address = Address::find($request->address_id);
            }
            
            if (!$address) {
                $address = Address::create([
                    'user_id' => $request->user_type == 'existing' ? $request->user_id : null,
                    'label' => 'Order Address',
                    'full_name' => $request->full_name,
                    'mobile' => $request->mobile,
                    'address_line1' => $request->address_line1,
                    'address_line2' => $request->address_line2,
                    'city' => $request->city,
                    'district' => $request->district,
                    'province' => $request->province,
                    'postal_code' => $request->postal_code,
                    'delivery_instructions' => $request->delivery_instructions,
                    'is_default' => false,
                ]);
            }

            // Generate order number
            $orderNumber = 'ORD-' . strtoupper(uniqid());

            // Create order
            $order = Order::create([
                'order_number' => $orderNumber,
                'user_id' => $request->user_type == 'existing' ? $request->user_id : null,
                'guest_email' => $request->user_type == 'guest' ? $request->guest_email : null,
                'guest_name' => $request->user_type == 'guest' ? $request->guest_name : null,
                'guest_mobile' => $request->user_type == 'guest' ? $request->guest_mobile : null,
                'total_amount' => $subtotal,
                'subtotal' => $subtotal,
                'discount_amount' => $totalDiscount,
                'shipping_amount' => $shippingAmount,
                'tax_amount' => $taxAmount,
                'grand_total' => $grandTotal,
                'shipping_address_id' => $address->id,
                'billing_address_id' => $address->id,
                'shipping_method' => $request->shipping_method,
                'payment_method' => $request->payment_method,
                'payment_status' => $request->payment_status,
                'order_status' => $request->order_status,
                'coupon_code' => $appliedCouponCode,
                'coupon_discount' => $couponDiscount,
                'notes' => $request->notes,
                'placed_at' => now(),
            ]);

            // Create order items and update inventory
            foreach ($items as $item) {
                $product = Product::find($item['product_id']);
                
                $mainImage = $product->images()->where('is_main', true)->first();
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'product_name' => $product->name,
                    'product_sku' => $product->sku,
                    'product_image' => $mainImage ? $mainImage->image_path : null,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $item['quantity'] * $item['unit_price'],
                ]);

                // Update inventory
                $inventory = Inventory::where('product_id', $item['product_id'])->first();
                if ($inventory) {
                    if ($inventory->quantity_on_hand < $item['quantity']) {
                        throw new \Exception("Insufficient stock for product: {$product->name}. Available: {$inventory->quantity_on_hand}");
                    }
                    $inventory->decrement('quantity_on_hand', $item['quantity']);
                    $inventory->increment('quantity_sold', $item['quantity']);
                }
            }
            
            // Record coupon usage if coupon was applied
            if ($coupon && $couponDiscount > 0 && $order->user_id) {
                $coupon->applyUsage($order->user_id, $order->id, $couponDiscount);
            }

            DB::commit();

            return redirect()->route('admin.orders.show', $order->id)
                ->with('success', 'Manual order created successfully! Order #: ' . $orderNumber);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to create order: ' . $e->getMessage())
                ->withInput();
        }
    }
}
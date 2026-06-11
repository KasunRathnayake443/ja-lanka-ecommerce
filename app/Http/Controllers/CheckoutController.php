<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Address;
use App\Models\Inventory;
use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function index()
    {
        // Get cart items
        $cartSession = $this->getCartSession();
        $cartItems = Cart::where(function($query) use ($cartSession) {
                $query->where('cart_session', $cartSession)
                      ->orWhere('user_id', Auth::id());
            })
            ->with('product.images')
            ->get();

        if ($cartItems->count() === 0) {
            return redirect()->route('shop')->with('error', 'Your cart is empty');
        }

        // Calculate subtotal
        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal += $item->quantity * $item->price;
        }
        
        $shipping = 350;
        
        // Get coupon from session if applied
        $coupon = null;
        $discount = 0;
        
        if (session()->has('coupon')) {
            $couponCode = session('coupon.code');
            $coupon = Coupon::where('code', $couponCode)->first();
            if ($coupon && $coupon->isValid($subtotal, Auth::id())) {
                $discount = $coupon->calculateDiscount($subtotal);
            } else {
                session()->forget('coupon');
            }
        }
        
        $total = $subtotal + $shipping - $discount;

        // Get user's saved addresses
        $addresses = Address::where('user_id', Auth::id())->get();
        
        // Store checkout data in session
        session(['checkout_data' => [
            'items' => $cartItems->toArray(),
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'discount' => $discount,
            'total' => $total,
            'coupon_code' => $coupon ? $coupon->code : null,
            'coupon_discount' => $discount
        ]]);

        return view('checkout.index', compact('cartItems', 'subtotal', 'shipping', 'discount', 'total', 'addresses', 'coupon'));
    }

    // Apply coupon via AJAX
    public function applyCoupon(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string|exists:coupons,code'
        ]);

        $checkoutData = session('checkout_data');
        if (!$checkoutData) {
            return response()->json(['success' => false, 'message' => 'Please refresh the page']);
        }

        $coupon = Coupon::where('code', $request->coupon_code)->first();
        $subtotal = $checkoutData['subtotal'];
        
        if (!$coupon->isValid($subtotal, Auth::id())) {
            return response()->json(['success' => false, 'message' => 'Coupon is invalid or expired']);
        }

        $discount = $coupon->calculateDiscount($subtotal);
        $total = $subtotal + $checkoutData['shipping'] - $discount;

        // Update session
        session(['coupon' => [
            'code' => $coupon->code,
            'discount' => $discount
        ]]);
        
        $checkoutData['discount'] = $discount;
        $checkoutData['total'] = $total;
        $checkoutData['coupon_code'] = $coupon->code;
        $checkoutData['coupon_discount'] = $discount;
        session(['checkout_data' => $checkoutData]);

        return response()->json([
            'success' => true,
            'discount' => $discount,
            'total' => $total,
            'subtotal' => $subtotal,
            'message' => 'Coupon applied successfully!'
        ]);
    }

    // Remove coupon
    public function removeCoupon()
    {
        $checkoutData = session('checkout_data');
        if ($checkoutData) {
            $checkoutData['discount'] = 0;
            $checkoutData['total'] = $checkoutData['subtotal'] + $checkoutData['shipping'];
            $checkoutData['coupon_code'] = null;
            $checkoutData['coupon_discount'] = 0;
            session(['checkout_data' => $checkoutData]);
        }
        
        session()->forget('coupon');
        
        return response()->json([
            'success' => true,
            'message' => 'Coupon removed'
        ]);
    }

    public function placeOrder(Request $request)
    {
        $request->validate([
            'address_id' => 'required|exists:addresses,id',
            'payment_method' => 'required|in:cod,card',
            'notes' => 'nullable|string|max:500'
        ]);
    
        $checkoutData = session('checkout_data');
        
        if (!$checkoutData || empty($checkoutData['items'])) {
            return redirect()->route('cart')->with('error', 'Your cart is empty');
        }
    
        // Check stock availability
        foreach ($checkoutData['items'] as $item) {
            $product = Product::find($item['product_id']);
            if (!$product) {
                return back()->with('error', 'Product not found');
            }
            
            $inventory = Inventory::where('product_id', $product->id)->first();
            if ($inventory && $inventory->quantity_on_hand < $item['quantity']) {
                return back()->with('error', "Sorry, {$product->name} is out of stock. Only {$inventory->quantity_on_hand} available.");
            }
        }
    
        DB::beginTransaction();
    
        try {
            $orderNumber = 'ORD-' . date('Ymd') . '-' . strtoupper(uniqid());
    
            $order = Order::create([
                'order_number' => $orderNumber,
                'user_id' => Auth::id(),
                'shipping_address_id' => $request->address_id,
                'total_amount' => $checkoutData['total'],
                'subtotal' => $checkoutData['subtotal'],
                'shipping_amount' => $checkoutData['shipping'],
                'discount_amount' => $checkoutData['discount'] ?? 0,
                'grand_total' => $checkoutData['total'],
                'coupon_code' => $checkoutData['coupon_code'] ?? null,
                'coupon_discount' => $checkoutData['coupon_discount'] ?? 0,
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',
                'order_status' => 'pending',
                'notes' => $request->notes,
                'placed_at' => now(),
            ]);
    
            // Create order items and update inventory
            foreach ($checkoutData['items'] as $item) {
                $product = Product::with('images')->find($item['product_id']);
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_sku' => $product->sku,
                    'product_image' => $product->images->first()->image_path ?? null,
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                    'total_price' => $item['quantity'] * $item['price'],
                ]);
    
                $inventory = Inventory::where('product_id', $product->id)->first();
                if ($inventory) {
                    $inventory->decrement('quantity_on_hand', $item['quantity']);
                    $inventory->increment('quantity_sold', $item['quantity']);
                }
            }
    
            // Apply coupon usage
            if (!empty($checkoutData['coupon_code']) && $checkoutData['coupon_discount'] > 0) {
                $coupon = Coupon::where('code', $checkoutData['coupon_code'])->first();
                if ($coupon) {
                    $coupon->increment('used_count');
                    
                    CouponUsage::create([
                        'coupon_id' => $coupon->id,
                        'user_id' => Auth::id(),
                        'order_id' => $order->id,
                        'discount_amount' => $checkoutData['coupon_discount'],
                    ]);
                }
            }
    
            // Clear cart
            $cartSession = $this->getCartSession();
            Cart::where(function($query) use ($cartSession) {
                    $query->where('cart_session', $cartSession)
                          ->orWhere('user_id', Auth::id());
                })->delete();
    
            session()->forget(['checkout_data', 'coupon']);
    
            DB::commit();
    
            if ($request->payment_method === 'cod') {
                return redirect()->route('checkout.success', $order->id)
                    ->with('success', 'Order placed successfully!');
            }
    
            return redirect()->route('checkout.payment', $order->id);
    
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order placement failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to place order. Please try again.');
        }
    }
    
    public function success($orderId)
    {
        $order = Order::where('user_id', Auth::id())
            ->with(['items.product', 'shippingAddress'])
            ->findOrFail($orderId);
            
        return view('checkout.success', compact('order'));
    }

    public function payment($orderId)
    {
        $order = Order::where('user_id', Auth::id())->findOrFail($orderId);
        return view('checkout.payment', compact('order'));
    }

    private function getCartSession()
    {
        if (!session()->has('cart_session')) {
            session()->put('cart_session', uniqid('cart_'));
        }
        return session()->get('cart_session');
    }
}
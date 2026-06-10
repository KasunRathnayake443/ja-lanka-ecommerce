<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function checkout()
    {
        // Get cart items
        $cartSession = session()->get('cart_session', uniqid('cart_'));
        $cartItems = Cart::where('cart_session', $cartSession)
            ->orWhere('user_id', Auth::id())
            ->with('product')
            ->get();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Your cart is empty');
        }
        
        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal += $item->quantity * $item->price;
        }
        
        $shipping = 350; // Default shipping
        $tax = $subtotal * 0.05; // 5% tax
        $total = $subtotal + $shipping + $tax;
        
        $addresses = Auth::check() ? Address::where('user_id', Auth::id())->get() : collect();
        
        return view('checkout', compact('cartItems', 'subtotal', 'shipping', 'tax', 'total', 'addresses'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email',
            'mobile' => 'required|string|max:20',
            'address_line1' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'district' => 'required|string|max:100',
            'shipping_method' => 'required|in:standard,express,pickup',
            'payment_method' => 'required|in:card,cod,bank_transfer',
        ]);
        
        // Get cart items
        $cartSession = session()->get('cart_session', uniqid('cart_'));
        $cartItems = Cart::where('cart_session', $cartSession)
            ->orWhere('user_id', Auth::id())
            ->with('product')
            ->get();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Your cart is empty');
        }
        
        // Calculate totals
        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal += $item->quantity * $item->price;
        }
        
        $shipping = $request->shipping_method === 'express' ? 650 : ($request->shipping_method === 'pickup' ? 0 : 350);
        $tax = $subtotal * 0.05;
        $grandTotal = $subtotal + $shipping + $tax;
        
        // Create or get address
        $address = null;
        if (Auth::check() && $request->save_address) {
            $address = Address::create([
                'user_id' => Auth::id(),
                'full_name' => $request->full_name,
                'mobile' => $request->mobile,
                'address_line1' => $request->address_line1,
                'address_line2' => $request->address_line2,
                'city' => $request->city,
                'district' => $request->district,
                'province' => $request->province,
                'postal_code' => $request->postal_code,
                'is_default' => !Address::where('user_id', Auth::id())->exists(),
            ]);
        }
        
        DB::beginTransaction();
        
        try {
            // Create order
            $order = Order::create([
                'order_number' => 'ORD-' . strtoupper(uniqid()),
                'user_id' => Auth::id(),
                'guest_email' => Auth::check() ? null : $request->email,
                'guest_name' => Auth::check() ? null : $request->full_name,
                'guest_mobile' => Auth::check() ? null : $request->mobile,
                'total_amount' => $subtotal,
                'subtotal' => $subtotal,
                'discount_amount' => 0,
                'shipping_amount' => $shipping,
                'tax_amount' => $tax,
                'grand_total' => $grandTotal,
                'shipping_address_id' => $address ? $address->id : null,
                'shipping_method' => $request->shipping_method,
                'payment_method' => $request->payment_method,
                'payment_status' => $request->payment_method === 'cod' ? 'pending' : 'pending',
                'order_status' => 'pending',
                'placed_at' => now(),
            ]);
            
            // Create order items
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name,
                    'product_sku' => $item->product->sku,
                    'product_image' => $item->product->images->first()?->image_path,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->price,
                    'total_price' => $item->quantity * $item->price,
                ]);
            }
            
            // Clear cart
            Cart::where('cart_session', $cartSession)
                ->orWhere('user_id', Auth::id())
                ->delete();
            
            DB::commit();
            
            // Clear cart session
            session()->forget('cart_session');
            
            return redirect()->route('order.confirmation', $order->order_number)
                ->with('success', 'Order placed successfully!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to place order. Please try again.');
        }
    }
    
    public function confirmation($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->with('items.product')
            ->firstOrFail();
        
        return view('order.confirmation', compact('order'));
    }
    
    public function track($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->firstOrFail();
        
        return view('order.track', compact('order'));
    }
}
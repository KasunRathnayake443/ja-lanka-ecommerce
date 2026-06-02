<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    public function getCart()
    {
        try {
            $cart = $this->getCartItems();
            return response()->json($cart);
        } catch (\Exception $e) {
            Log::error('Cart error: ' . $e->getMessage());
            return response()->json([
                'items' => [],
                'total' => 0,
                'count' => 0
            ]);
        }
    }

    public function add(Request $request)
    {
        try {
            $request->validate([
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1'
            ]);

            $product = Product::find($request->product_id);
            
            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found'
                ], 404);
            }
            
            $cartSession = $this->getCartSession();
            
            // Check if sale is active
            $now = now();
            $hasActiveSale = false;
            
            if ($product->sale_price && $product->sale_price < $product->regular_price) {
                $saleStartValid = is_null($product->sale_start_date) || $product->sale_start_date <= $now;
                $saleEndValid = is_null($product->sale_end_date) || $product->sale_end_date >= $now;
                $hasActiveSale = $saleStartValid && $saleEndValid;
            }
            
            $priceToUse = $hasActiveSale ? $product->sale_price : $product->regular_price;
            
            // Find existing cart item
            $existingItem = Cart::where(function($query) use ($cartSession) {
                    $query->where('cart_session', $cartSession)
                          ->orWhere('user_id', Auth::id());
                })
                ->where('product_id', $request->product_id)
                ->first();
            
            if ($existingItem) {
                $existingItem->update([
                    'quantity' => $existingItem->quantity + $request->quantity
                ]);
            } else {
                Cart::create([
                    'cart_session' => $cartSession,
                    'product_id' => $request->product_id,
                    'user_id' => Auth::id(),
                    'quantity' => $request->quantity,
                    'price' => $priceToUse
                ]);
            }

            return response()->json([
                'success' => true,
                'cart_count' => $this->getCartCount(),
                'message' => 'Product added to cart'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Add to cart error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error adding to cart'
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $cartItem = Cart::findOrFail($id);
            
            if ($request->has('quantity')) {
                $newQuantity = $request->quantity;
                if ($newQuantity < 1) {
                    $cartItem->delete();
                } else {
                    $cartItem->update(['quantity' => $newQuantity]);
                }
            }

            return response()->json([
                'success' => true,
                'cart_count' => $this->getCartCount()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating cart'
            ], 500);
        }
    }

    public function remove($id)
    {
        try {
            $cartItem = Cart::findOrFail($id);
            $cartItem->delete();

            return response()->json([
                'success' => true,
                'cart_count' => $this->getCartCount()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error removing item'
            ], 500);
        }
    }

    private function getCartSession()
    {
        if (!Session::has('cart_session')) {
            Session::put('cart_session', uniqid('cart_'));
        }
        return Session::get('cart_session');
    }

    private function getCartItems()
    {
        $cartSession = $this->getCartSession();
        
        $items = Cart::where('cart_session', $cartSession)
            ->orWhere('user_id', Auth::id())
            ->with('product.images')
            ->get();

        $total = 0;
        foreach ($items as $item) {
            $total += $item->quantity * $item->price;
        }

        return [
            'items' => $items,
            'total' => $total,
            'count' => $items->sum('quantity')
        ];
    }

    private function getCartCount()
    {
        $cartSession = $this->getCartSession();
        return Cart::where('cart_session', $cartSession)
            ->orWhere('user_id', Auth::id())
            ->sum('quantity');
    }
}
@extends('layouts.mobile')

@section('title', 'Checkout')

@section('content')
<div class="pb-32">
    
    <!-- Header -->
    <div class="bg-white border-b border-gray-100 px-5 py-4 sticky top-0 z-10">
        <div class="flex items-center gap-3">
            <a href="{{ route('mobile.cart') }}" class="text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h1 class="text-xl font-bold text-gray-800">Checkout</h1>
        </div>
    </div>
    
    <form action="{{ route('mobile.checkout.place-order') }}" method="POST" id="checkoutForm">
        @csrf
        
        <!-- Shipping Address Section -->
        <div class="bg-white border-b border-gray-100 p-5">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Shipping Address</h2>
            
            @if($addresses->count() > 0)
                <div class="space-y-3 mb-4">
                    @foreach($addresses as $address)
                    <label class="flex items-start gap-3 p-4 border rounded-xl cursor-pointer hover:bg-gray-50">
                        <input type="radio" name="address_id" value="{{ $address->id }}" 
                               {{ $loop->first ? 'checked' : '' }} required class="mt-1">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="font-semibold text-gray-800">{{ $address->label }}</span>
                                @if($address->is_default)
                                    <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full">Default</span>
                                @endif
                            </div>
                            <p class="text-sm text-gray-600">{{ $address->full_name }}</p>
                            <p class="text-sm text-gray-600">{{ $address->mobile }}</p>
                            <p class="text-sm text-gray-600">{{ $address->address_line1 }}</p>
                            @if($address->address_line2)
                                <p class="text-sm text-gray-600">{{ $address->address_line2 }}</p>
                            @endif
                            <p class="text-sm text-gray-600">{{ $address->city }}, {{ $address->district }}</p>
                        </div>
                    </label>
                    @endforeach
                </div>
                
                <div class="text-center">
                    <a href="{{ route('mobile.account.addresses') }}" class="text-red-600 text-sm font-medium">
                        + Add New Address
                    </a>
                </div>
            @else
                <div class="text-center py-8">
                    <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <p class="text-gray-500 mb-4">No saved addresses</p>
                    <a href="{{ route('mobile.account.addresses') }}" class="bg-red-600 text-white px-6 py-2 rounded-lg text-sm inline-block">
                        Add New Address
                    </a>
                </div>
            @endif
        </div>
        
        <!-- Order Notes -->
        <div class="bg-white border-b border-gray-100 p-5">
            <h2 class="text-lg font-semibold text-gray-800 mb-3">Order Notes (Optional)</h2>
            <textarea name="notes" rows="3" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:border-red-500 focus:outline-none" 
                      placeholder="Add delivery instructions or special requests..."></textarea>
        </div>
        
        <!-- Payment Method -->
        <div class="bg-white border-b border-gray-100 p-5">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Payment Method</h2>
            <div class="space-y-3">
                <label class="flex items-center gap-3 p-4 border rounded-xl cursor-pointer hover:bg-gray-50">
                    <input type="radio" name="payment_method" value="cod" checked required>
                    <div>
                        <span class="font-semibold text-gray-800">Cash on Delivery</span>
                        <p class="text-sm text-gray-500">Pay when you receive your order</p>
                    </div>
                </label>
                <label class="flex items-center gap-3 p-4 border rounded-xl cursor-pointer hover:bg-gray-50">
                    <input type="radio" name="payment_method" value="card">
                    <div>
                        <span class="font-semibold text-gray-800">Credit / Debit Card</span>
                        <p class="text-sm text-gray-500">Pay securely online (Coming soon)</p>
                    </div>
                </label>
            </div>
        </div>
        
        <!-- Order Summary -->
        <div class="p-5">
            <div class="bg-gray-50 rounded-xl p-4">
                <h2 class="text-lg font-semibold text-gray-800 mb-3">Order Summary</h2>
                
                <!-- Cart Items -->
                <div class="space-y-3 mb-4 max-h-60 overflow-y-auto">
                    @foreach($cartItems as $item)
                    <div class="flex gap-3 pb-3 border-b border-gray-200">
                        <div class="w-16 h-16 bg-white rounded-lg overflow-hidden flex-shrink-0">
                            @if($item->product && $item->product->images->first())
                                <img src="{{ asset('storage/' . $item->product->images->first()->image_path) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400">📷</div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <p class="font-medium text-sm text-gray-800">{{ $item->product->name ?? 'Product' }}</p>
                            <p class="text-xs text-gray-500">Qty: {{ $item->quantity }}</p>
                            <p class="text-sm font-semibold text-red-600">LKR {{ number_format($item->price, 2) }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <!-- Coupon Section -->
                <div class="border-t border-gray-200 pt-4 mb-4">
                    <div class="flex gap-2" id="couponSection">
                        @if($coupon)
                            <div class="flex-1 bg-green-50 border border-green-200 rounded-xl p-3 flex justify-between items-center">
                                <div>
                                    <span class="text-green-700 font-medium">{{ $coupon->code }}</span>
                                    <span class="text-xs text-green-600 ml-2">-LKR {{ number_format($discount, 2) }}</span>
                                </div>
                                <button type="button" onclick="removeCoupon()" class="text-red-500 text-sm hover:text-red-700">Remove</button>
                            </div>
                        @else
                            <input type="text" id="couponCode" placeholder="Coupon code" class="flex-1 px-4 py-3 border border-gray-200 rounded-xl text-sm">
                            <button type="button" onclick="applyCoupon()" class="bg-gray-800 text-white px-5 py-3 rounded-xl text-sm hover:bg-gray-700">
                                Apply
                            </button>
                        @endif
                    </div>
                    <div id="couponMessage" class="text-xs mt-2 hidden"></div>
                </div>
                
                <!-- Totals -->
                <div class="space-y-2 pt-2">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Subtotal:</span>
                        <span class="font-semibold text-gray-800">LKR {{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Shipping:</span>
                        <span class="font-semibold text-gray-800">LKR {{ number_format($shipping, 2) }}</span>
                    </div>
                    @if($discount > 0)
                    <div class="flex justify-between text-green-600">
                        <span>Discount:</span>
                        <span>-LKR {{ number_format($discount, 2) }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between border-t border-gray-200 pt-2 mt-2">
                        <span class="text-base font-bold text-gray-800">Total:</span>
                        <span class="text-base font-bold text-red-600">LKR {{ number_format($total, 2) }}</span>
                    </div>
                </div>
                
                <!-- Place Order Button moved inside the summary -->
                <button type="submit" form="checkoutForm" class="w-full mt-6 bg-red-600 text-white py-3 rounded-xl font-semibold hover:bg-red-700 transition">
                    Place Order
                </button>
                
                <p class="text-xs text-gray-500 text-center mt-4">
                    By placing this order, you agree to our Terms and Conditions
                </p>
            </div>
        </div>
        
    </form>
    
</div>

<script>
async function applyCoupon() {
    const code = document.getElementById('couponCode').value;
    if (!code) {
        showCouponMessage('Please enter a coupon code', 'error');
        return;
    }
    
    try {
        const response = await fetch('{{ route("mobile.checkout.apply-coupon") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ coupon_code: code })
        });
        
        const data = await response.json();
        
        if (data.success) {
            location.reload();
        } else {
            showCouponMessage(data.message, 'error');
        }
    } catch (error) {
        showCouponMessage('Error applying coupon', 'error');
    }
}

async function removeCoupon() {
    try {
        const response = await fetch('{{ route("mobile.checkout.remove-coupon") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });
        
        const data = await response.json();
        if (data.success) {
            location.reload();
        }
    } catch (error) {
        console.error('Error:', error);
    }
}

function showCouponMessage(message, type) {
    const msgDiv = document.getElementById('couponMessage');
    msgDiv.textContent = message;
    msgDiv.className = `text-xs mt-2 ${type === 'error' ? 'text-red-600' : 'text-green-600'}`;
    msgDiv.classList.remove('hidden');
    
    setTimeout(() => {
        msgDiv.classList.add('hidden');
    }, 3000);
}
</script>
@endsection
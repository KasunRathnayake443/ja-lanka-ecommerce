@extends('admin.layouts.app')

@section('page_title', 'Coupons')

@section('content')
<div class="bg-white rounded-xl shadow-md overflow-hidden">
    <div class="px-6 py-4 border-b bg-gray-50 flex justify-between items-center">
        <div>
            <h2 class="text-xl font-semibold">Coupon Management</h2>
            <p class="text-sm text-gray-500">Create and manage discount coupons</p>
        </div>
        <a href="{{ route('admin.coupons.create') }}" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">
            + Add Coupon
        </a>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Code</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Discount</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Minimum Order</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Usage</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Valid Until</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($coupons as $coupon)
                <tr>
                    <td class="px-6 py-4">
                        <span class="font-mono font-bold text-gray-900">{{ $coupon->code }}</span>
                        @if($coupon->description)
                            <div class="text-xs text-gray-500">{{ $coupon->description }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($coupon->discount_type == 'percentage')
                            <span class="text-green-600 font-semibold">{{ $coupon->discount_value }}% OFF</span>
                        @else
                            <span class="text-green-600 font-semibold">LKR {{ number_format($coupon->discount_value, 2) }} OFF</span>
                        @endif
                        @if($coupon->maximum_discount)
                            <div class="text-xs text-gray-500">Max LKR {{ number_format($coupon->maximum_discount, 2) }}</div>
                        @endif
                     </td>
                    <td class="px-6 py-4">
                        @if($coupon->minimum_order > 0)
                            LKR {{ number_format($coupon->minimum_order, 2) }}
                        @else
                            <span class="text-gray-400">No minimum</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div>{{ $coupon->used_count }} / {{ $coupon->usage_limit ?? '∞' }}</div>
                        <div class="text-xs text-gray-500">{{ $coupon->usage_limit_per_user }} per user</div>
                    </td>
                    <td class="px-6 py-4">
                        @if($coupon->expires_at)
                            {{ $coupon->expires_at->format('M d, Y') }}
                            @if($coupon->expires_at->isPast())
                                <span class="text-red-500 text-xs block">Expired</span>
                            @endif
                        @else
                            <span class="text-gray-400">Never</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full {{ $coupon->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $coupon->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('admin.coupons.edit', $coupon->id) }}" class="text-blue-600 hover:text-blue-900 mr-2">Edit</a>
                        <a href="{{ route('admin.coupons.usage', $coupon->id) }}" class="text-green-600 hover:text-green-900 mr-2">Usage</a>
                        <form action="{{ route('admin.coupons.toggle-status', $coupon->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-yellow-600 hover:text-yellow-900 mr-2" onclick="return confirm('Toggle coupon status?')">
                                {{ $coupon->is_active ? 'Disable' : 'Enable' }}
                            </button>
                        </form>
                        <form action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Delete this coupon?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">No coupons found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="px-6 py-4 border-t">
        {{ $coupons->links() }}
    </div>
</div>
@endsection
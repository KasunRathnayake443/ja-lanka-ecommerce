@extends('admin.layouts.app')

@section('page_title', 'Coupon Usage')

@section('content')
<div class="bg-white rounded-xl shadow-md overflow-hidden">
    <div class="px-6 py-4 border-b bg-gray-50">
        <h2 class="text-xl font-semibold">Coupon Usage: {{ $coupon->code }}</h2>
        <p class="text-sm text-gray-500">{{ $coupon->used_count }} / {{ $coupon->usage_limit ?? '∞' }} total uses</p>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">User</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Order #</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Discount Applied</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Date Used</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($coupon->usages as $usage)
                <tr>
                    <td class="px-6 py-4">
                        @if($usage->user)
                            {{ $usage->user->name }}
                            <div class="text-xs text-gray-500">{{ $usage->user->email }}</div>
                        @else
                            <span class="text-gray-400">User deleted</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <a href="{{ route('admin.orders.show', $usage->order_id) }}" class="text-blue-600 hover:underline">
                            {{ $usage->order->order_number ?? 'N/A' }}
                        </a>
                    </td>
                    <td class="px-6 py-4 text-green-600 font-semibold">LKR {{ number_format($usage->discount_amount, 2) }}</td>
                    <td class="px-6 py-4 text-sm">{{ $usage->created_at->format('M d, Y h:i A') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center text-gray-500">No usage records found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
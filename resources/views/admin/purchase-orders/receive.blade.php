@extends('admin.layouts.app')

@section('page_title', 'Receive Stock')

@section('content')
<div class="bg-white rounded-xl shadow-md overflow-hidden">
    <div class="px-6 py-4 border-b bg-gray-50 flex justify-between items-center">
        <div>
            <h2 class="text-xl font-semibold">Receive Stock</h2>
            <p class="text-sm text-gray-500">Purchase Order: {{ $purchaseOrder->po_number }}</p>
        </div>
        <a href="{{ route('admin.purchase-orders.show', $purchaseOrder->id) }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm">
            Back to PO
        </a>
    </div>

    <div class="p-6">
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <p class="text-sm text-blue-700">
                <strong>Supplier:</strong> {{ $purchaseOrder->supplier->name }}<br>
                <strong>Order Date:</strong> {{ $purchaseOrder->order_date->format('M d, Y') }}
            </p>
        </div>

        <form action="{{ route('admin.purchase-orders.receive', $purchaseOrder->id) }}" method="POST">
            @csrf
            <div class="overflow-x-auto">
                <table class="w-full border">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-sm font-medium">Product</th>
                            <th class="px-4 py-2 text-center text-sm font-medium">Ordered</th>
                            <th class="px-4 py-2 text-center text-sm font-medium">Already Received</th>
                            <th class="px-4 py-2 text-center text-sm font-medium">Remaining</th>
                            <th class="px-4 py-2 text-center text-sm font-medium">Quantity to Receive *</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($purchaseOrder->items as $item)
                            @php
                                $remaining = $item->quantity_ordered - $item->quantity_received;
                            @endphp
                            <tr>
                                <td class="px-4 py-2">
                                    {{ $item->product->name ?? 'N/A' }}
                                    <div class="text-xs text-gray-500">SKU: {{ $item->product->sku ?? 'N/A' }}</div>
                                </td>
                                <td class="px-4 py-2 text-center">{{ $item->quantity_ordered }}</td>
                                <td class="px-4 py-2 text-center">{{ $item->quantity_received }}</td>
                                <td class="px-4 py-2 text-center">
                                    <span class="font-medium {{ $remaining > 0 ? 'text-blue-600' : 'text-green-600' }}">
                                        {{ $remaining }}
                                    </span>
                                </td>
                                <td class="px-4 py-2 text-center">
                                    @if($remaining > 0)
                                        <input type="number" name="items[{{ $loop->index }}][quantity_received]" 
                                               min="1" max="{{ $remaining }}" value="{{ $remaining }}"
                                               class="w-24 px-3 py-2 border rounded-lg text-center">
                                        <input type="hidden" name="items[{{ $loop->index }}][product_id]" value="{{ $item->product_id }}">
                                    @else
                                        <span class="text-green-600 text-sm">Fully Received</span>
                                        <input type="hidden" name="items[{{ $loop->index }}][quantity_received]" value="0">
                                        <input type="hidden" name="items[{{ $loop->index }}][product_id]" value="{{ $item->product_id }}">
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t mt-6">
                <a href="{{ route('admin.purchase-orders.show', $purchaseOrder->id) }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">Cancel</a>
                <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    Receive Stock
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
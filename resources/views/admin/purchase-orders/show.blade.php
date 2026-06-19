@extends('admin.layouts.app')

@section('page_title', 'Purchase Order Details')

@section('content')
<div class="bg-white rounded-xl shadow-md overflow-hidden">
    <div class="px-6 py-4 border-b bg-gray-50 flex justify-between items-center">
        <div>
            <h2 class="text-xl font-semibold">Purchase Order</h2>
            <p class="text-sm text-gray-500">{{ $purchaseOrder->po_number }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.purchase-orders.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm">
                Back
            </a>
            @if($purchaseOrder->status == 'draft')
                <a href="{{ route('admin.purchase-orders.edit', $purchaseOrder->id) }}" class="bg-amber-500 hover:bg-amber-600 text-white px-4 py-2 rounded-lg text-sm">
                    Edit
                </a>
            @endif
        </div>
    </div>

    <div class="p-6">
        <!-- Status Badge -->
        <div class="mb-6">
            <span class="px-3 py-1 text-sm rounded-full bg-{{ $purchaseOrder->status_color }}-100 text-{{ $purchaseOrder->status_color }}-800">
                Status: {{ $purchaseOrder->status_label }}
            </span>
            @if($purchaseOrder->restockRequest)
                <a href="{{ route('admin.restock.show', $purchaseOrder->restockRequest->id) }}" class="ml-4 text-blue-600 hover:underline">
                    From Request: {{ $purchaseOrder->restockRequest->request_number }}
                </a>
            @endif
        </div>

        <!-- PO Info -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-sm text-gray-500">Supplier</p>
                <p class="font-medium">{{ $purchaseOrder->supplier->name ?? 'N/A' }}</p>
                <p class="text-sm text-gray-500">{{ $purchaseOrder->supplier->email ?? '' }}</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-sm text-gray-500">Order Date</p>
                <p class="font-medium">{{ $purchaseOrder->order_date->format('M d, Y') }}</p>
                <p class="text-sm text-gray-500">Created by: {{ $purchaseOrder->createdBy->name ?? 'N/A' }}</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-sm text-gray-500">Expected Delivery</p>
                <p class="font-medium">{{ $purchaseOrder->expected_delivery_date ? $purchaseOrder->expected_delivery_date->format('M d, Y') : 'Not set' }}</p>
                @if($purchaseOrder->actual_delivery_date)
                    <p class="text-sm text-green-600">Received: {{ $purchaseOrder->actual_delivery_date->format('M d, Y') }}</p>
                @endif
            </div>
        </div>

        <!-- Items Table -->
        <div class="mb-6">
            <h3 class="text-lg font-medium mb-4">Products</h3>
            <div class="overflow-x-auto">
                <table class="w-full border">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-sm font-medium">Product</th>
                            <th class="px-4 py-2 text-left text-sm font-medium">SKU</th>
                            <th class="px-4 py-2 text-center text-sm font-medium">Ordered</th>
                            <th class="px-4 py-2 text-center text-sm font-medium">Received</th>
                            <th class="px-4 py-2 text-right text-sm font-medium">Unit Cost</th>
                            <th class="px-4 py-2 text-right text-sm font-medium">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($purchaseOrder->items as $item)
                        <tr>
                            <td class="px-4 py-2">
                                <a href="{{ route('admin.products.edit', $item->product_id) }}" class="text-blue-600 hover:underline">
                                    {{ $item->product->name ?? 'N/A' }}
                                </a>
                            </td>
                            <td class="px-4 py-2 text-sm">{{ $item->product->sku ?? 'N/A' }}</td>
                            <td class="px-4 py-2 text-center">{{ $item->quantity_ordered }}</td>
                            <td class="px-4 py-2 text-center">{{ $item->quantity_received }}</td>
                            <td class="px-4 py-2 text-right">LKR {{ number_format($item->unit_cost, 2) }}</td>
                            <td class="px-4 py-2 text-right font-medium">LKR {{ number_format($item->total_cost, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr>
                            <td colspan="4" class="px-4 py-2 text-right font-medium">Subtotal:</td>
                            <td colspan="2" class="px-4 py-2 text-right font-medium">LKR {{ number_format($purchaseOrder->subtotal, 2) }}</td>
                        </tr>
                        <tr>
                            <td colspan="4" class="px-4 py-2 text-right font-medium">Tax:</td>
                            <td colspan="2" class="px-4 py-2 text-right">LKR {{ number_format($purchaseOrder->tax_amount, 2) }}</td>
                        </tr>
                        <tr>
                            <td colspan="4" class="px-4 py-2 text-right font-medium">Shipping:</td>
                            <td colspan="2" class="px-4 py-2 text-right">LKR {{ number_format($purchaseOrder->shipping_amount, 2) }}</td>
                        </tr>
                        <tr class="border-t-2">
                            <td colspan="4" class="px-4 py-2 text-right font-bold text-lg">Grand Total:</td>
                            <td colspan="2" class="px-4 py-2 text-right font-bold text-lg text-purple-600">LKR {{ number_format($purchaseOrder->grand_total, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Actions -->
        <div class="border-t pt-6">
            <h3 class="text-lg font-medium mb-4">Actions</h3>
            <div class="flex flex-wrap gap-3">
                @if($purchaseOrder->status == 'draft')
                    <form action="{{ route('admin.purchase-orders.send', $purchaseOrder->id) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">
                            Send to Supplier
                        </button>
                    </form>
                    <form action="{{ route('admin.purchase-orders.cancel', $purchaseOrder->id) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm" onclick="return confirm('Cancel this purchase order?')">
                            Cancel
                        </button>
                    </form>
                @endif

                @if($purchaseOrder->status == 'sent')
                    <form action="{{ route('admin.purchase-orders.receive', $purchaseOrder->id) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm">
                            Mark as Received
                        </button>
                    </form>
                    <form action="{{ route('admin.purchase-orders.cancel', $purchaseOrder->id) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm" onclick="return confirm('Cancel this purchase order?')">
                            Cancel
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Notes -->
        @if($purchaseOrder->notes)
        <div class="border-t pt-6 mt-6">
            <h3 class="text-lg font-medium mb-4">Notes</h3>
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-gray-700">{{ $purchaseOrder->notes }}</p>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
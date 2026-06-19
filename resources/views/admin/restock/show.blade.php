@extends('admin.layouts.app')

@section('page_title', 'Restock Request Details')

@section('content')
<div class="bg-white rounded-xl shadow-md overflow-hidden">
    <div class="px-6 py-4 border-b bg-gray-50 flex justify-between items-center">
        <div>
            <h2 class="text-xl font-semibold">Restock Request</h2>
            <p class="text-sm text-gray-500">{{ $restockRequest->request_number }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.restock.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm">
                Back
            </a>
            @if($restockRequest->status == 'draft')
                <a href="{{ route('admin.restock.edit', $restockRequest->id) }}" class="bg-amber-500 hover:bg-amber-600 text-white px-4 py-2 rounded-lg text-sm">
                    Edit
                </a>
            @endif
        </div>
    </div>

    <div class="p-6">
        <!-- Status Badge -->
        <div class="mb-6">
            <span class="px-3 py-1 text-sm rounded-full bg-{{ $restockRequest->status_color }}-100 text-{{ $restockRequest->status_color }}-800">
                Status: {{ $restockRequest->status_label }}
            </span>
            @if($restockRequest->purchaseOrder)
                <a href="{{ route('admin.purchase-orders.show', $restockRequest->purchaseOrder->id) }}" class="ml-4 text-blue-600 hover:underline">
                    PO: {{ $restockRequest->purchaseOrder->po_number }}
                </a>
            @endif
        </div>

        <!-- Request Info -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-sm text-gray-500">Supplier</p>
                <p class="font-medium">{{ $restockRequest->supplier->name ?? 'N/A' }}</p>
                <p class="text-sm text-gray-500">{{ $restockRequest->supplier->email ?? '' }}</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-sm text-gray-500">Request Date</p>
                <p class="font-medium">{{ $restockRequest->request_date ? $restockRequest->request_date->format('M d, Y') : 'N/A' }}</p>
                <p class="text-sm text-gray-500">Created by: {{ $restockRequest->createdBy->name ?? 'N/A' }}</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-4">
                <p class="text-sm text-gray-500">Expected Delivery</p>
                <p class="font-medium">{{ $restockRequest->expected_delivery_date ? $restockRequest->expected_delivery_date->format('M d, Y') : 'Not set' }}</p>
                @if($restockRequest->actual_delivery_date)
                    <p class="text-sm text-green-600">Received: {{ $restockRequest->actual_delivery_date->format('M d, Y') }}</p>
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
                            <th class="px-4 py-2 text-center text-sm font-medium">Requested</th>
                            <th class="px-4 py-2 text-center text-sm font-medium">Received</th>
                            <th class="px-4 py-2 text-center text-sm font-medium">Remaining</th>
                            <th class="px-4 py-2 text-right text-sm font-medium">Unit Cost</th>
                            <th class="px-4 py-2 text-right text-sm font-medium">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($restockRequest->items as $item)
                        <tr>
                            <td class="px-4 py-2">
                                <a href="{{ route('admin.products.edit', $item->product_id) }}" class="text-blue-600 hover:underline">
                                    {{ $item->product->name ?? 'N/A' }}
                                </a>
                            </td>
                            <td class="px-4 py-2 text-sm">{{ $item->product->sku ?? 'N/A' }}</td>
                            <td class="px-4 py-2 text-center">{{ $item->quantity_requested }}</td>
                            <td class="px-4 py-2 text-center">{{ $item->quantity_received }}</td>
                            <td class="px-4 py-2 text-center">
                                <span class="{{ $item->remaining_quantity > 0 ? 'text-red-600' : 'text-green-600' }}">
                                    {{ $item->remaining_quantity }}
                                </span>
                            </td>
                            <td class="px-4 py-2 text-right">LKR {{ number_format($item->unit_cost ?? 0, 2) }}</td>
                            <td class="px-4 py-2 text-right">LKR {{ number_format($item->total_cost ?? 0, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Actions -->
        <div class="border-t pt-6">
            <h3 class="text-lg font-medium mb-4">Actions</h3>
            <div class="flex flex-wrap gap-3">
                @if($restockRequest->status == 'draft')
                    <form action="{{ route('admin.restock.send', $restockRequest->id) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">
                            Send to Supplier
                        </button>
                    </form>
                    <form action="{{ route('admin.restock.cancel', $restockRequest->id) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm" onclick="return confirm('Cancel this request?')">
                            Cancel Request
                        </button>
                    </form>
                @endif

                @if($restockRequest->status == 'sent')
                    <form action="{{ route('admin.restock.acknowledge', $restockRequest->id) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm">
                            Acknowledge (Supplier Confirmed)
                        </button>
                    </form>
                    <form action="{{ route('admin.restock.cancel', $restockRequest->id) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm" onclick="return confirm('Cancel this request?')">
                            Cancel Request
                        </button>
                    </form>
                @endif

                @if($restockRequest->status == 'acknowledged' && !$restockRequest->purchaseOrder)
                    <a href="{{ route('admin.purchase-orders.create', ['restock_request_id' => $restockRequest->id]) }}" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm">
                        Create Purchase Order
                    </a>
                @endif

                @if($restockRequest->status == 'ordered' || $restockRequest->status == 'partially_received')
                    <button type="button" onclick="openReceiveModal()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm">
                        Receive Stock
                    </button>
                @endif

                @if($restockRequest->status == 'received')
                    <form action="{{ route('admin.restock.close', $restockRequest->id) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm">
                            Close Request
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <!-- Notes -->
        @if($restockRequest->notes || $restockRequest->admin_notes)
        <div class="border-t pt-6 mt-6">
            <h3 class="text-lg font-medium mb-4">Notes</h3>
            @if($restockRequest->notes)
                <div class="bg-gray-50 rounded-lg p-4 mb-2">
                    <p class="text-sm font-medium text-gray-500">Supplier Notes</p>
                    <p class="text-gray-700">{{ $restockRequest->notes }}</p>
                </div>
            @endif
            @if($restockRequest->admin_notes)
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm font-medium text-gray-500">Admin Notes</p>
                    <p class="text-gray-700">{{ $restockRequest->admin_notes }}</p>
                </div>
            @endif
        </div>
        @endif

        <!-- Status History -->
        <div class="border-t pt-6 mt-6">
            <h3 class="text-lg font-medium mb-4">Status History</h3>
            <div class="space-y-2">
                @foreach($restockRequest->statusHistory as $history)
                    <div class="flex items-center gap-4 text-sm border-b pb-2">
                        <span class="text-gray-500">{{ $history->created_at->format('M d, Y H:i') }}</span>
                        <span class="px-2 py-1 text-xs rounded-full bg-gray-200 text-gray-700">
                            {{ $history->old_status_label ?? 'New' }}
                        </span>
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                        <span class="px-2 py-1 text-xs rounded-full bg-{{ $history->newStatus?->color ?? 'gray' }}-100 text-{{ $history->newStatus?->color ?? 'gray' }}-800">
                            {{ $history->new_status_label }}
                        </span>
                        <span class="text-gray-500">by {{ $history->performedBy->name ?? 'System' }}</span>
                        @if($history->notes)
                            <span class="text-gray-400 text-xs">- {{ $history->notes }}</span>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Receive Stock Modal -->
<div id="receiveModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4 p-6">
        <h3 class="text-lg font-medium mb-4">Receive Stock</h3>
        <form action="{{ route('admin.restock.receive', $restockRequest->id) }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Product</label>
                    <select name="product_id" required class="w-full px-3 py-2 border rounded-lg">
                        @foreach($restockRequest->items as $item)
                            @if($item->remaining_quantity > 0)
                                <option value="{{ $item->product_id }}">
                                    {{ $item->product->name }} ({{ $item->remaining_quantity }} remaining)
                                </option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Quantity to Receive *</label>
                    <input type="number" name="quantity" required min="1" class="w-full px-3 py-2 border rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                    <textarea name="notes" rows="2" class="w-full px-3 py-2 border rounded-lg"></textarea>
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-6 pt-4 border-t">
                <button type="button" onclick="closeReceiveModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">Cancel</button>
                <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Receive Stock</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function openReceiveModal() {
    document.getElementById('receiveModal').classList.remove('hidden');
    document.getElementById('receiveModal').classList.add('flex');
}

function closeReceiveModal() {
    document.getElementById('receiveModal').classList.add('hidden');
    document.getElementById('receiveModal').classList.remove('flex');
}
</script>
@endpush
@endsection
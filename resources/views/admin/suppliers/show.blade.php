@extends('admin.layouts.app')

@section('page_title', 'Supplier Details')

@section('content')
<div class="bg-white rounded-xl shadow-md overflow-hidden">
    <div class="px-6 py-4 border-b bg-gray-50 flex justify-between items-center">
        <div>
            <h2 class="text-xl font-semibold">{{ $supplier->name }}</h2>
            <p class="text-sm text-gray-500">Supplier Details</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.suppliers.edit', $supplier->id) }}" class="bg-amber-500 hover:bg-amber-600 text-white px-4 py-2 rounded-lg text-sm">
                Edit
            </a>
            <a href="{{ route('admin.suppliers.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm">
                Back
            </a>
        </div>
    </div>

    <div class="p-6">
        <!-- Supplier Info -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div>
                <h3 class="text-sm font-medium text-gray-500">Supplier Information</h3>
                <div class="mt-3 space-y-2">
                    <p><span class="font-medium">Name:</span> {{ $supplier->name }}</p>
                    <p><span class="font-medium">Contact Person:</span> {{ $supplier->contact_person ?? 'N/A' }}</p>
                    <p><span class="font-medium">Email:</span> {{ $supplier->email ?? 'N/A' }}</p>
                    <p><span class="font-medium">Phone:</span> {{ $supplier->phone ?? 'N/A' }}</p>
                    <p><span class="font-medium">Mobile:</span> {{ $supplier->mobile ?? 'N/A' }}</p>
                    <p><span class="font-medium">Website:</span> {{ $supplier->website ?? 'N/A' }}</p>
                </div>
            </div>
            <div>
                <h3 class="text-sm font-medium text-gray-500">Address & Additional Info</h3>
                <div class="mt-3 space-y-2">
                    <p><span class="font-medium">Address:</span> {{ $supplier->full_address ?? 'N/A' }}</p>
                    <p><span class="font-medium">Tax Number:</span> {{ $supplier->tax_number ?? 'N/A' }}</p>
                    <p><span class="font-medium">Payment Terms:</span> {{ $supplier->payment_terms ?? 'N/A' }}</p>
                    <p><span class="font-medium">Status:</span> 
                        <span class="px-2 py-1 text-xs rounded-full {{ $supplier->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $supplier->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </p>
                    <p><span class="font-medium">Products:</span> {{ $supplier->products()->count() }}</p>
                </div>
            </div>
        </div>

        <!-- Products List -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold mb-4 border-b pb-2">Products Supplied</h3>
            @if($supplier->products->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-sm font-medium">Product</th>
                                <th class="px-4 py-2 text-left text-sm font-medium">SKU</th>
                                <th class="px-4 py-2 text-left text-sm font-medium">Category</th>
                                <th class="px-4 py-2 text-center text-sm font-medium">Stock</th>
                                <th class="px-4 py-2 text-center text-sm font-medium">Supplier SKU</th>
                                <th class="px-4 py-2 text-right text-sm font-medium">Supplier Price</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($supplier->products as $product)
                            <tr>
                                <td class="px-4 py-2">
                                    <a href="{{ route('admin.products.edit', $product->id) }}" class="text-blue-600 hover:underline">
                                        {{ $product->name }}
                                    </a>
                                </td>
                                <td class="px-4 py-2 text-sm">{{ $product->sku }}</td>
                                <td class="px-4 py-2 text-sm">{{ $product->category->name ?? 'N/A' }}</td>
                                <td class="px-4 py-2 text-center">
                                    <span class="px-2 py-1 text-xs rounded-full 
                                        {{ ($product->inventory->quantity_on_hand ?? 0) <= ($product->inventory->reorder_level ?? 5) ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                        {{ $product->inventory->quantity_on_hand ?? 0 }}
                                    </span>
                                </td>
                                <td class="px-4 py-2 text-center text-sm">{{ $product->pivot->supplier_sku ?? 'N/A' }}</td>
                                <td class="px-4 py-2 text-right text-sm">LKR {{ $product->pivot->supplier_price ?? 'N/A' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-500 text-sm">No products assigned to this supplier.</p>
            @endif
        </div>

        <!-- Notes -->
        @if($supplier->notes)
        <div>
            <h3 class="text-lg font-semibold mb-4 border-b pb-2">Notes</h3>
            <p class="text-gray-700">{{ $supplier->notes }}</p>
        </div>
        @endif
    </div>
</div>
@endsection
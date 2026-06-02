@extends('admin.layouts.app')

@section('page_title', 'Banners')

@section('content')
<div class="bg-white rounded-xl shadow-md overflow-hidden">
    <div class="px-6 py-4 border-b bg-gray-50 flex justify-between items-center">
        <div>
            <h2 class="text-xl font-semibold">Banner Management</h2>
            <p class="text-sm text-gray-500">Manage homepage hero banners</p>
        </div>
        <a href="{{ route('admin.banners.create') }}" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">
            + Add Banner
        </a>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Image</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Title</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Button</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Order</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500">Status</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($banners as $banner)
                <tr>
                    <td class="px-6 py-4">
                        <img src="{{ asset('storage/' . $banner->image) }}" class="w-16 h-12 object-cover rounded">
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-medium">{{ $banner->title }}</div>
                        @if($banner->subtitle)
                            <div class="text-sm text-gray-500">{{ $banner->subtitle }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($banner->button_text)
                            <span class="text-sm">{{ $banner->button_text }}</span>
                        @else
                            <span class="text-sm text-gray-400">None</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">{{ $banner->display_order }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full {{ $banner->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $banner->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('admin.banners.edit', $banner->id) }}" class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>
                        <form action="{{ route('admin.banners.destroy', $banner->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Delete this banner?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                        No banners yet. <a href="{{ route('admin.banners.create') }}" class="text-red-600">Create your first banner</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
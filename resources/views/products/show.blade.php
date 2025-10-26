@extends('layouts.app')

@section('title', 'Product Details: ' . $product->name)

@section('content')
<div class="container mx-auto px-4 py-8 max-w-4xl">
    <!-- Header with Actions -->
    <div class="flex justify-between items-center mb-8">
        <div class="flex items-center">
            <div class="bg-blue-100 p-3 rounded-full mr-4">
                <span class="text-blue-600 text-2xl">📦</span>
            </div>
            <div>
                <h1 class="text-3xl font-bold text-gray-800">{{ $product->name }}</h1>
                <p class="text-gray-600 mt-1">Product Details</p>
            </div>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('products.edit', $product->id) }}"
               class="bg-yellow-600 text-white px-6 py-2 rounded-lg hover:bg-yellow-700 transition duration-200 font-semibold">
                ✏️ Edit Product
            </a>
            <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="inline"
                  onsubmit="return confirm('Are you sure you want to delete this product?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition duration-200 font-semibold">
                    🗑️ Delete Product
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Product Image -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            @if($product->image)
                <img src="{{ asset('images/' . $product->image) }}"
                     alt="{{ $product->name }}"
                     class="w-full h-96 object-cover">
            @else
                <div class="w-full h-96 bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                    <div class="text-center">
                        <div class="text-6xl mb-4">📷</div>
                        <p class="text-gray-500 text-lg">No Image Available</p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Product Details -->
        <div class="bg-white rounded-xl shadow-lg p-8">
            <div class="space-y-6">
                <!-- Status Badge -->
                <div class="flex items-center justify-between">
                    <span class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Status</span>
                    <span class="px-3 py-1 rounded-full text-sm font-semibold
                        {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $product->is_active ? '🟢 Active' : '🔴 Inactive' }}
                    </span>
                </div>

                <!-- Price -->
                <div class="border-t pt-6">
                    <span class="text-sm font-semibold text-gray-500 uppercase tracking-wide block mb-2">Price</span>
                    <div class="flex items-baseline">
                        <span class="text-4xl font-bold text-green-600">${{ number_format($product->price, 2) }}</span>
                        <span class="text-gray-500 ml-2">USD</span>
                    </div>
                </div>

                <!-- Category -->
                <div class="border-t pt-6">
                    <span class="text-sm font-semibold text-gray-500 uppercase tracking-wide block mb-2">Category</span>
                    @if($product->category)
                        <a href="{{ route('categories.show', $product->category->id) }}"
                           class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 hover:bg-blue-200 transition duration-200">
                            📁 {{ $product->category->name }}
                        </a>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                            📁 No category
                        </span>
                    @endif
                </div>

                <!-- Stock Quantity -->
                @if($product->stock_quantity !== null)
                    <div class="border-t pt-6">
                        <span class="text-sm font-semibold text-gray-500 uppercase tracking-wide block mb-2">Stock Quantity</span>
                        <div class="flex items-center">
                            <span class="text-2xl mr-3 {{ $product->stock_quantity > 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $product->stock_quantity > 0 ? '📦' : '⚠️' }}
                            </span>
                            <span class="text-xl font-semibold {{ $product->stock_quantity > 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $product->stock_quantity }} {{ Str::plural('item', $product->stock_quantity) }}
                                @if($product->stock_quantity == 0)
                                    (Out of Stock)
                                @elseif($product->stock_quantity <= 5)
                                    (Low Stock)
                                @else
                                    (In Stock)
                                @endif
                            </span>
                        </div>
                    </div>
                @endif

                <!-- Description -->
                <div class="border-t pt-6">
                    <span class="text-sm font-semibold text-gray-500 uppercase tracking-wide block mb-3">Description</span>
                    @if($product->description)
                        <p class="text-gray-700 leading-relaxed">{{ $product->description }}</p>
                    @else
                        <p class="text-gray-500 italic">No description provided.</p>
                    @endif
                </div>

                <!-- Timestamps -->
                <div class="border-t pt-6">
                    <span class="text-sm font-semibold text-gray-500 uppercase tracking-wide block mb-3">Product Information</span>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-500">Created:</span>
                            <span class="font-medium">{{ $product->created_at->format('M j, Y \a\t g:i A') }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500">Last Updated:</span>
                            <span class="font-medium">{{ $product->updated_at->format('M j, Y \a\t g:i A') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <div class="mt-8 flex justify-between items-center">
        <a href="{{ route('products.index') }}"
           class="inline-flex items-center bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 transition duration-200 font-semibold">
            ← Back to All Products
        </a>

        <div class="flex gap-3">
            <a href="{{ route('products.edit', $product->id) }}"
               class="bg-yellow-600 text-white px-6 py-3 rounded-lg hover:bg-yellow-700 transition duration-200 font-semibold">
                ✏️ Edit This Product
            </a>
            <a href="{{ route('products.create') }}"
               class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition duration-200 font-semibold">
                ➕ Add New Product
            </a>
        </div>
    </div>
</div>
@endsection

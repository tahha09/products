@extends('layouts.app')

@section('title', 'Category: ' . $category->name)

@section('content')
<div class="container mx-auto px-4 py-8 max-w-6xl">
    <!-- Header with Actions -->
    <div class="flex justify-between items-center mb-8">
        <div class="flex items-center">
            <div class="bg-blue-100 p-3 rounded-full mr-4">
                <span class="text-blue-600 text-2xl">📁</span>
            </div>
            <div>
                <h1 class="text-3xl font-bold text-gray-800">{{ $category->name }}</h1>
                <p class="text-gray-600 mt-1">Category Details</p>
            </div>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('categories.edit', $category->id) }}"
               class="bg-yellow-600 text-white px-6 py-2 rounded-lg hover:bg-yellow-700 transition duration-200 font-semibold">
                ✏️ Edit Category
            </a>
            <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="inline"
                  onsubmit="return confirm('Are you sure you want to delete this category?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition duration-200 font-semibold">
                    🗑️ Delete Category
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Category Image and Info -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                @if($category->image)
                    <img src="{{ asset('images/categories/' . $category->image) }}"
                         alt="{{ $category->name }}"
                         class="w-full h-64 object-cover">
                @else
                    <div class="w-full h-64 bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center">
                        <div class="text-center">
                            <div class="text-6xl mb-4">📁</div>
                            <p class="text-gray-500 text-lg">No Image Available</p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Category Stats -->
            <div class="bg-white rounded-xl shadow-lg p-6 mt-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Category Statistics</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Total Products:</span>
                        <span class="font-semibold text-blue-600">{{ $category->products->count() }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Status:</span>
                        <span class="px-2 py-1 rounded-full text-sm font-semibold
                            {{ $category->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $category->is_active ? '🟢 Active' : '🔴 Inactive' }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Created:</span>
                        <span class="font-medium text-sm">{{ $category->created_at->format('M j, Y') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Category Details and Products -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Category Description -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Description</h3>
                @if($category->description)
                    <p class="text-gray-700 leading-relaxed">{{ $category->description }}</p>
                @else
                    <p class="text-gray-500 italic">No description provided.</p>
                @endif
            </div>

            <!-- Products in this Category -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-semibold text-gray-800">
                        Products in this Category ({{ $category->products->count() }})
                    </h3>
                    <a href="{{ route('products.create') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition duration-200 text-sm">
                        + Add Product
                    </a>
                </div>

                @if($category->products->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($category->products as $product)
                            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition duration-200">
                                <div class="flex items-start space-x-4">
                                    <!-- Product Image -->
                                    <div class="flex-shrink-0">
                                        @if($product->image)
                                            <img src="{{ asset('images/' . $product->image) }}"
                                                 alt="{{ $product->name }}"
                                                 class="w-16 h-16 object-cover rounded-lg">
                                        @else
                                            <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center">
                                                <span class="text-gray-400 text-sm">📦</span>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Product Info -->
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-sm font-medium text-gray-900 truncate">{{ $product->name }}</h4>
                                        <p class="text-sm text-gray-500 mt-1">${{ number_format($product->price, 2) }}</p>
                                        <div class="flex items-center mt-2 space-x-2">
                                            @if($product->is_active)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                                    Active
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                                    Inactive
                                                </span>
                                            @endif
                                            @if($product->stock_quantity !== null)
                                                <span class="text-xs text-gray-500">
                                                    Stock: {{ $product->stock_quantity }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Actions -->
                                    <div class="flex-shrink-0">
                                        <a href="{{ route('products.show', $product->id) }}"
                                           class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                            View →
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="text-4xl mb-4">📦</div>
                        <h4 class="text-lg font-medium text-gray-600 mb-2">No products in this category</h4>
                        <p class="text-gray-500 mb-4">Start by adding some products to this category.</p>
                        <a href="{{ route('products.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-200">
                            Add First Product
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <div class="mt-8 flex justify-between items-center">
        <a href="{{ route('categories.index') }}"
           class="inline-flex items-center bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 transition duration-200 font-semibold">
            ← Back to All Categories
        </a>

        <div class="flex gap-3">
            <a href="{{ route('categories.edit', $category->id) }}"
               class="bg-yellow-600 text-white px-6 py-3 rounded-lg hover:bg-yellow-700 transition duration-200 font-semibold">
                ✏️ Edit This Category
            </a>
            <a href="{{ route('products.index') }}"
               class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition duration-200 font-semibold">
                📦 View All Products
            </a>
        </div>
    </div>
</div>
@endsection

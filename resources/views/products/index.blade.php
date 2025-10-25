@extends('layouts.app')

@section('title', 'All Products')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">All Products</h1>
        <a href="{{ route('products.create') }}" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition duration-200">
            + Add New Product
        </a>
    </div>

    <!-- Search Bar -->
    <div class="mb-8">
        <form method="GET" action="{{ route('products.index') }}" class="flex gap-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products by name or category..."
                   class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition duration-200">
                🔍 Search
            </button>
            @if(request('search'))
                <a href="{{ route('products.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition duration-200">
                    Clear
                </a>
            @endif
        </form>
    </div>

    @if($products->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($products as $product)
                <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <!-- Image -->
                    <div class="relative">
                        @if($product->image)
                            <img src="{{ asset('images/' . $product->image) }}"
                                 alt="{{ $product->name }}"
                                 class="h-48 w-full object-cover">
                        @else
                            <div class="h-48 w-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                                <span class="text-gray-400 text-lg">📷 No Image</span>
                            </div>
                        @endif
                        @if(!$product->is_active)
                            <span class="absolute top-2 right-2 bg-red-500 text-white px-2 py-1 rounded-full text-xs font-semibold">
                                Inactive
                            </span>
                        @endif
                    </div>

                    <div class="p-5">
                        <!-- Product Info -->
                        <div class="mb-3">
                            <h3 class="text-xl font-bold text-gray-800 mb-1 line-clamp-2">{{ $product->name }}</h3>
                            <p class="text-sm text-gray-500 mb-2">{{ $product->category }}</p>
                            <div class="flex items-center justify-between">
                                <span class="text-2xl font-bold text-green-600">${{ number_format($product->price, 2) }}</span>
                                @if($product->stock_quantity !== null)
                                    <span class="text-sm {{ $product->stock_quantity > 0 ? 'text-green-600' : 'text-red-600' }}">
                                        Stock: {{ $product->stock_quantity }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Description Preview -->
                        @if($product->description)
                            <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $product->description }}</p>
                        @endif

                        <!-- Action Buttons -->
                        <div class="flex flex-col gap-2">
                            <a href="{{ route('products.show', $product->id) }}"
                               class="bg-blue-600 text-white text-center py-2 px-4 rounded-lg hover:bg-blue-700 transition duration-200">
                                👁️ View Details
                            </a>
                            <div class="flex gap-2">
                                <a href="{{ route('products.edit', $product->id) }}"
                                   class="flex-1 bg-yellow-500 text-white text-center py-2 px-3 rounded-lg hover:bg-yellow-600 transition duration-200 text-sm">
                                    ✏️ Edit
                                </a>
                                <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="flex-1"
                                      onsubmit="return confirm('Are you sure you want to delete this product?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full bg-red-600 text-white py-2 px-3 rounded-lg hover:bg-red-700 transition duration-200 text-sm">
                                        🗑️ Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $products->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <div class="text-6xl mb-4">📦</div>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">No products found</h3>
            <p class="text-gray-500 mb-6">
                @if(request('search'))
                    No products match your search criteria. Try different keywords or <a href="{{ route('products.index') }}" class="text-blue-600 hover:underline">clear the search</a>.
                @else
                    Get started by adding your first product.
                @endif
            </p>
            <a href="{{ route('products.create') }}" class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition duration-200">
                + Add First Product
            </a>
        </div>
    @endif
</div>
@endsection

@extends('layouts.app')

@section('title', 'All Products')

@section('content')
<h2 class="text-2xl font-bold mb-6 text-gray-800">All Products</h2>

<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
    @foreach($products as $product)
        <div class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition">
            <img src="{{ asset('images/' . $product['image']) }}" alt="{{ $product['name'] }}" class="h-48 w-full object-cover">
            <div class="p-4">
                <h3 class="text-lg font-semibold text-gray-800">{{ $product['name'] }}</h3>
                <p class="text-gray-600 mb-2">${{ $product['price'] }}</p>
                <a href="{{ route('products.show', $product['id']) }}" class="inline-block bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700">
                    View Details
                </a>
            </div>
        </div>
    @endforeach
</div>
@endsection

@extends('layouts.app')

@section('title', $product['name'])

@section('content')
<div class="bg-white rounded-xl shadow-md p-6 max-w-2xl mx-auto">
    <img src="{{ asset('images/' . $product['image']) }}" alt="{{ $product['name'] }}" class="w-full h-80 object-cover rounded mb-6">

    <h2 class="text-3xl font-bold text-gray-800 mb-2">{{ $product['name'] }}</h2>
    <p class="text-lg text-gray-600 mb-4"><strong>Price:</strong> ${{ $product['price'] }}</p>
    <p class="text-gray-700 mb-6">{{ $product['description'] }}</p>

    <a href="{{ route('products.index') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        ← Back to Products
    </a>
</div>
@endsection

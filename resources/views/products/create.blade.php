@extends('layouts.app')

@section('title', 'Add Product')

@section('content')
<h2 class="text-2xl font-bold mb-6 text-gray-800">Add New Product</h2>

<form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data"
      class="bg-white p-6 rounded-xl shadow-md max-w-lg">
    @csrf

    <div class="mb-4">
        <label class="block font-semibold mb-1">Product Name</label>
        <input type="text" name="name" class="border rounded w-full p-2" required>
    </div>

    <div class="mb-4">
        <label class="block font-semibold mb-1">Price</label>
        <input type="number" name="price" class="border rounded w-full p-2" required>
    </div>

    <div class="mb-4">
        <label class="block font-semibold mb-1">Description</label>
        <textarea name="description" rows="4" class="border rounded w-full p-2" required></textarea>
    </div>

    <div class="mb-6">
        <label class="block font-semibold mb-1">Image</label>
        <input type="file" name="image" class="border rounded w-full p-2" accept="image/*" required>
    </div>

    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
        Save Product
    </button>
</form>
@endsection

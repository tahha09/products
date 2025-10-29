@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-900">Trashed Products</h1>
                    <a href="{{ route('products.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Back to Products
                    </a>
                </div>

                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                @if($products->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($products as $product)
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $product->name }}</h3>
                                        <p class="text-sm text-gray-600">{{ $product->category->name ?? 'No Category' }}</p>
                                        <p class="text-sm text-gray-500">Price: ${{ number_format($product->price, 2) }}</p>
                                        <p class="text-sm text-gray-500">Deleted: {{ $product->deleted_at->diffForHumans() }}</p>
                                    </div>
                                    @if($product->image)
                                        <img src="{{ asset('images/' . $product->image) }}" alt="{{ $product->name }}" class="w-16 h-16 object-cover rounded">
                                    @endif
                                </div>

                                <div class="flex space-x-2">
                                    <form action="{{ route('products.restore', $product->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="bg-green-500 hover:bg-green-700 text-white text-sm font-bold py-1 px-3 rounded">
                                            Restore
                                        </button>
                                    </form>

                                    <form action="{{ route('products.forceDelete', $product->id) }}" method="POST" class="inline"
                                          onsubmit="return confirm('Are you sure you want to permanently delete this product?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white text-sm font-bold py-1 px-3 rounded">
                                            Delete Forever
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6">
                        {{ $products->links() }}
                    </div>
                @else
                    <div class="text-center py-8">
                        <p class="text-gray-500 text-lg">No trashed products found.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

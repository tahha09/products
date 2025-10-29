@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-2xl font-bold text-gray-900">Trashed Categories</h1>
                    <a href="{{ route('categories.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Back to Categories
                    </a>
                </div>

                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                @if($categories->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($categories as $category)
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $category->name }}</h3>
                                        <p class="text-sm text-gray-600">{{ $category->description ?? 'No Description' }}</p>
                                        <p class="text-sm text-gray-500">Deleted: {{ $category->deleted_at->diffForHumans() }}</p>
                                    </div>
                                    @if($category->image)
                                        <img src="{{ asset('images/categories/' . $category->image) }}" alt="{{ $category->name }}" class="w-16 h-16 object-cover rounded">
                                    @endif
                                </div>

                                <div class="flex space-x-2">
                                    <form action="{{ route('categories.restore', $category->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="bg-green-500 hover:bg-green-700 text-white text-sm font-bold py-1 px-3 rounded">
                                            Restore
                                        </button>
                                    </form>

                                    <form action="{{ route('categories.forceDelete', $category->id) }}" method="POST" class="inline"
                                          onsubmit="return confirm('Are you sure you want to permanently delete this category?')">
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
                        {{ $categories->links() }}
                    </div>
                @else
                    <div class="text-center py-8">
                        <p class="text-gray-500 text-lg">No trashed categories found.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

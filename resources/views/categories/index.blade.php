@extends('layouts.app')

@section('title', 'All Categories')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-8">
            <div class="flex items-center">
                <div class="bg-blue-100 p-3 rounded-full mr-4">
                    <span class="text-blue-600 text-2xl">📁</span>
                </div>
                <h1 class="text-3xl font-bold text-gray-800">{{ auth()->check() ? 'My Categories' : 'All Categories' }}</h1>
            </div>
            @auth
                <div class="flex gap-4">
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('categories.trashed') }}" class="bg-orange-600 text-white px-4 py-2 rounded-lg hover:bg-orange-700 transition duration-200">
                            🗂️ Trashed Categories
                        </a>
                    @endif
                    <a href="{{ route('categories.create') }}"
                        class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition duration-200">
                        + Add New Category
                    </a>
                </div>
            @endauth
        </div>

        <!-- Search Bar -->
        <div class="mb-8">
            <form method="GET" action="{{ route('categories.index') }}" class="flex gap-4">
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Search categories by name or description..."
                    class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <button type="submit"
                    class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition duration-200">
                    🔍 Search
                </button>
                @if (request('search'))
                    <a href="{{ route('categories.index') }}"
                        class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition duration-200">
                        Clear
                    </a>
                @endif
            </form>
        </div>

        @if ($categories->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach ($categories as $category)
                    <div
                        class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                        <!-- Image -->
                        <div class="relative">
                            @if ($category->image)
                                <img src="{{ asset('images/categories/' . $category->image) }}" alt="{{ $category->name }}"
                                    class="h-48 w-full object-cover">
                            @else
                                <div
                                    class="h-48 w-full bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center">
                                    <span class="text-blue-400 text-lg">📁</span>
                                </div>
                            @endif
                            @if (!$category->is_active)
                                <span
                                    class="absolute top-2 right-2 bg-red-500 text-white px-2 py-1 rounded-full text-xs font-semibold">
                                    Inactive
                                </span>
                            @endif
                        </div>

                        <div class="p-5">
                            <!-- Category Info -->
                            <div class="mb-3">
                                <h3 class="text-xl font-bold text-gray-800 mb-1 line-clamp-2">{{ $category->name }}</h3>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-blue-600 bg-blue-100 px-2 py-1 rounded-full">
                                        📦 {{ $category->products_count }}
                                        {{ Str::plural('product', $category->products_count) }}
                                    </span>
                                </div>
                            </div>

                            <!-- Description Preview -->
                            @if ($category->description)
                                <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $category->description }}</p>
                            @endif

                            <!-- Action Buttons -->
                            <div class="flex flex-col gap-2">
                                <a href="{{ route('categories.show', $category->id) }}"
                                    class="bg-blue-600 text-white text-center py-2 px-4 rounded-lg hover:bg-blue-700 transition duration-200">
                                    👁️ View Details
                                </a>
                                @auth
                                    <div class="flex gap-2">
                                        <a href="{{ route('categories.edit', $category->id) }}"
                                            class="flex-1 bg-yellow-500 text-white text-center py-2 px-3 rounded-lg hover:bg-yellow-600 transition duration-200 text-sm">
                                            ✏️ Edit
                                        </a>
                                        <form action="{{ route('categories.destroy', $category->id) }}" method="POST"
                                            class="flex-1"
                                            onsubmit="return confirm('Are you sure you want to delete this category?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="w-full bg-red-600 text-white py-2 px-3 rounded-lg hover:bg-red-700 transition duration-200 text-sm">
                                                🗑️ Delete
                                            </button>
                                        </form>
                                    </div>
                                @endauth
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $categories->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <div class="text-6xl mb-4">📁</div>
                <h3 class="text-xl font-semibold text-gray-600 mb-2">No categories found</h3>
                <p class="text-gray-500 mb-6">
                    @if (request('search'))
                        No categories match your search criteria. Try different keywords or <a
                            href="{{ route('categories.index') }}" class="text-blue-600 hover:underline">clear the
                            search</a>.
                    @else
                        Get started by adding your first category.
                    @endif
                </p>
                @auth
                    <a href="{{ route('categories.create') }}"
                        class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition duration-200">
                        + Add First Category
                    </a>
                @else
                    <p class="text-gray-500">Please <a href="{{ route('login') }}" class="text-blue-600 hover:underline">login</a> to add categories.</p>
                @endauth
            </div>
        @endif

        <!-- Navigation -->
        <div class="mt-8 flex justify-center">
            <a href="{{ route('products.index') }}"
                class="bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 transition duration-200 font-semibold">
                ← Back to Products
            </a>
        </div>
    </div>
@endsection

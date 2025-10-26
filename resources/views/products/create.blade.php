@extends('layouts.app')

@section('title', 'Add New Product')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-2xl">
    <div class="bg-white rounded-xl shadow-lg p-8">
        <div class="flex items-center mb-6">
            <div class="bg-green-100 p-3 rounded-full mr-4">
                <span class="text-green-600 text-2xl">➕</span>
            </div>
            <h1 class="text-3xl font-bold text-gray-800">Add New Product</h1>
        </div>

        <!-- Error Messages -->
        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 p-4 rounded-lg mb-6">
                <div class="flex items-center mb-2">
                    <span class="text-red-500 mr-2">⚠️</span>
                    <strong>Please fix the following errors:</strong>
                </div>
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Product Name -->
                <div class="md:col-span-2">
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                        Product Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                           placeholder="Enter product name" required>
                </div>

                <!-- Price -->
                <div>
                    <label for="price" class="block text-sm font-semibold text-gray-700 mb-2">
                        Price <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-3 text-gray-500">$</span>
                        <input type="number" id="price" name="price" value="{{ old('price') }}"
                               class="w-full pl-8 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                               min="0" step="0.01" placeholder="0.00" required>
                    </div>
                </div>

                <!-- Category -->
                <div>
                    <label for="category_id" class="block text-sm font-semibold text-gray-700 mb-2">
                        Category
                    </label>
                    <select id="category_id" name="category_id"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
                        <option value="">No category</option>
                        @foreach(\App\Models\Category::where('is_active', true)->get() as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Stock Quantity -->
                <div>
                    <label for="stock_quantity" class="block text-sm font-semibold text-gray-700 mb-2">
                        Stock Quantity
                    </label>
                    <input type="number" id="stock_quantity" name="stock_quantity" value="{{ old('stock_quantity', 0) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200"
                           min="0" placeholder="0">
                </div>

                <!-- Active Status -->
                <div class="flex items-center">
                    <input type="checkbox" id="is_active" name="is_active"
                           class="w-5 h-5 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500"
                           {{ old('is_active', true) ? 'checked' : '' }}>
                    <label for="is_active" class="ml-3 text-sm font-semibold text-gray-700">
                        Active Product
                    </label>
                </div>
            </div>

            <!-- Description -->
            <div class="mt-6">
                <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">
                    Description
                </label>
                <textarea id="description" name="description" rows="4"
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 resize-vertical"
                          placeholder="Enter product description">{{ old('description') }}</textarea>
            </div>

            <!-- Image Upload -->
            <div class="mt-6">
                <label for="image" class="block text-sm font-semibold text-gray-700 mb-2">
                    Product Image
                </label>
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-400 transition duration-200">
                    <div class="space-y-2">
                        <div class="text-4xl">📷</div>
                        <div class="text-gray-600">
                            <label for="image" class="cursor-pointer">
                                <span class="text-blue-600 hover:text-blue-700 font-medium">Click to upload</span> or drag and drop
                            </label>
                            <input type="file" id="image" name="image" class="hidden" accept="image/*"
                                   onchange="previewImage(event)">
                        </div>
                        <p class="text-sm text-gray-500">PNG, JPG, GIF up to 2MB</p>
                    </div>
                </div>
                @if ($errors->has('image'))
                    <p class="text-red-600 text-sm mt-2">{{ $errors->first('image') }}</p>
                @endif

                <!-- Image Preview -->
                <div id="image-preview" class="mt-4 hidden">
                    <img id="preview-img" src="" alt="Preview" class="max-w-full h-48 object-cover rounded-lg mx-auto">
                    <button type="button" onclick="removeImage()" class="mt-2 text-red-600 hover:text-red-700 text-sm">
                        Remove image
                    </button>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="mt-8 flex gap-4">
                <button type="submit" class="flex-1 bg-green-600 text-white py-3 px-6 rounded-lg hover:bg-green-700 focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition duration-200 font-semibold">
                    💾 Save Product
                </button>
                <a href="{{ route('products.index') }}" class="bg-gray-500 text-white py-3 px-6 rounded-lg hover:bg-gray-600 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition duration-200 font-semibold">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function previewImage(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview-img').src = e.target.result;
            document.getElementById('image-preview').classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }
}

function removeImage() {
    document.getElementById('image').value = '';
    document.getElementById('image-preview').classList.add('hidden');
}
</script>
@endsection

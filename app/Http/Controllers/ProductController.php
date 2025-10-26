<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    // Display all products with search and pagination
    public function index(Request $request)
    {
        $query = Product::with('category');

        if (auth()->check()) {
            $query->where('user_id', auth()->id());
        }

        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where('name', 'like', "%$search%")
                ->orWhereHas('category', function ($q) use ($search) {
                    $q->where('name', 'like', "%$search%");
                });
        }

        $products = $query->latest()->paginate(9);

        return view('products.index', compact('products'));
    }

    // Display form to add new product
    public function create()
    {
        return view('products.create');
    }

    // Save new product
    public function store(ProductStoreRequest $request)
    {
        $validated = $request->validated();

        // Upload image if present
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $file = $request->file('image');
            $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))
                . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images'), $filename);
            $validated['image'] = $filename;
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['user_id'] = auth()->id();

        Product::create($validated);

        return redirect()->route('products.index')->with('success', 'Product created successfully!');
    }

    // Display product details
    public function show(Product $product)
    {
        if (auth()->check() && $product->user_id !== auth()->id()) {
            abort(403);
        }
        $product->load('category');
        return view('products.show', compact('product'));
    }


    // Display form to edit product
    public function edit(Product $product)
    {
        if ($product->user_id !== auth()->id()) {
            abort(403);
        }
        $product->load('category');
        return view('products.edit', compact('product'));
    }


    // Update product data
    public function update(ProductUpdateRequest $request, Product $product)
    {
        $validated = $request->validated();

        // Upload new image and delete old one
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            if ($product->image && file_exists(public_path('images/' . $product->image))) {
                unlink(public_path('images/' . $product->image));
            }

            $file = $request->file('image');
            $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))
                . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images'), $filename);
            $validated['image'] = $filename;
        }

        $validated['is_active'] = $request->has('is_active');

        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'Product updated successfully!');
    }

    // Delete product with image
    public function destroy(Product $product)
    {
        if ($product->user_id !== auth()->id()) {
            abort(403);
        }
        if ($product->image && file_exists(public_path('images/' . $product->image))) {
            unlink(public_path('images/' . $product->image));
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully!');
    }
}

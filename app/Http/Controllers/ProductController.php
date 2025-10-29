<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display all products with search and pagination.
     */
    public function index(Request $request)
    {
        $query = Product::with('category');
        $user = auth()->user();

        if ($user) {
            if ($user->isAdmin()) {
                // Admin sees all products
                $query = Product::with('category');
            } elseif ($user->isManager()) {
                // Manager sees only active products
                $query->where('is_active', true);
            } else {
                // User sees only their own products
                $query->where('user_id', $user->id);
            }
        }

        // Search functionality
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhereHas('category', function ($catQuery) use ($search) {
                        $catQuery->where('name', 'like', "%$search%");
                    });
            });
        }

        $products = $query->latest()->paginate(9);

        return view('products.index', compact('products'));
    }

    /**
     * Display form to add new product.
     */
    public function create()
    {
        $user = auth()->user();

        // Only admin and manager can create products
        if (!$user->isAdmin() && !$user->isManager()) {
            abort(403, 'You do not have permission to create products.');
        }

        return view('products.create');
    }

    /**
     * Store a newly created product.
     */
    public function store(ProductStoreRequest $request)
    {
        $user = auth()->user();

        if (!$user->isAdmin() && !$user->isManager()) {
            abort(403, 'You do not have permission to add products.');
        }

        $validated = $request->validated();

        // Handle image upload
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $file = $request->file('image');
            $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))
                . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images'), $filename);
            $validated['image'] = $filename;
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['user_id'] = $user->id;

        Product::create($validated);

        return redirect()->route('products.index')->with('success', 'Product created successfully!');
    }

    /**
     * Display product details.
     */
    public function show(Product $product)
    {
        $user = auth()->user();

        // Admin can view all products, others can only view their own
        if (!$user->isAdmin() && $product->user_id !== $user->id) {
            abort(403);
        }

        $product->load('category');
        return view('products.show', compact('product'));
    }

    /**
     * Display form to edit product.
     */
    public function edit(Product $product)
    {
        $user = auth()->user();

        // Admin can edit any product, others can only edit their own
        if (!$user->isAdmin() && $product->user_id !== $user->id) {
            abort(403);
        }

        $product->load('category');
        return view('products.edit', compact('product'));
    }

    /**
     * Update product data.
     */
    public function update(ProductUpdateRequest $request, Product $product)
    {
        $user = auth()->user();

        if (!$user->isAdmin() && $product->user_id !== $user->id) {
            abort(403);
        }

        $validated = $request->validated();

        // Handle new image upload if provided
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

    /**
     * Delete product with image.
     */
    public function destroy(Product $product)
    {
        $user = auth()->user();

        // Admin can delete any product, others can only delete their own
        if (!$user->isAdmin() && $product->user_id !== $user->id) {
            abort(403);
        }

        if ($product->image && file_exists(public_path('images/' . $product->image))) {
            unlink(public_path('images/' . $product->image));
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully!');
    }

    /**
     * Display trashed products.
     */
    public function trashed()
    {
        $user = auth()->user();

        // ✅ Only admin can view trashed products
        if (!$user->isAdmin()) {
            abort(403);
        }

        $products = Product::onlyTrashed()->with('category')->latest()->paginate(9);

        return view('products.trashed', compact('products'));
    }

    /**
     * Restore a trashed product.
     */
    public function restore($id)
    {
        $user = auth()->user();

        // ✅ Only admin can restore products
        if (!$user->isAdmin()) {
            abort(403);
        }

        $product = Product::onlyTrashed()->findOrFail($id);
        $product->restore();

        return redirect()->route('products.trashed')->with('success', 'Product restored successfully!');
    }

    /**
     * Permanently delete a product.
     */
    public function forceDelete($id)
    {
        $user = auth()->user();

        // ✅ Only admin can force delete products
        if (!$user->isAdmin()) {
            abort(403);
        }

        $product = Product::onlyTrashed()->findOrFail($id);

        if ($product->image && file_exists(public_path('images/' . $product->image))) {
            unlink(public_path('images/' . $product->image));
        }

        $product->forceDelete();

        return redirect()->route('products.trashed')->with('success', 'Product permanently deleted!');
    }
}

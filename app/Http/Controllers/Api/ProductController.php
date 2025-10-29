<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            $products = Product::with('category')->withTrashed()->get();
        } elseif ($user->isManager()) {
            $products = Product::with('category')->where('is_active', true)->get();
        } else {
            $products = Product::with('category')->where('user_id', $user->id)->get();
        }

        return response()->json($products);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $this->authorize('create', Product::class);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'status' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'stock_quantity' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['is_active'] = $request->has('is_active');

        $product = Product::create($validated);

        return response()->json($product->load('category'), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product): JsonResponse
    {
        $this->authorize('view', $product);

        return response()->json($product->load('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product): JsonResponse
    {
        $this->authorize('update', $product);

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'sometimes|required|numeric|min:0',
            'status' => 'nullable|string',
            'category_id' => 'sometimes|required|exists:categories,id',
            'stock_quantity' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $product->update($validated);

        return response()->json($product->load('category'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product): JsonResponse
    {
        $this->authorize('delete', $product);

        $product->delete();

        return response()->json(['message' => 'Product deleted successfully']);
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(string $id): JsonResponse
    {
        $product = Product::withTrashed()->findOrFail($id);
        $this->authorize('restore', $product);

        $product->restore();

        return response()->json(['message' => 'Product restored successfully']);
    }

    /**
     * Permanently delete the specified resource from storage.
     */
    public function forceDelete(string $id): JsonResponse
    {
        $product = Product::withTrashed()->findOrFail($id);
        $this->authorize('forceDelete', $product);

        $product->forceDelete();

        return response()->json(['message' => 'Product permanently deleted']);
    }
}

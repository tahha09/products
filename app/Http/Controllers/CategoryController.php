<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\CategoryStoreRequest;
use App\Http\Requests\CategoryUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Category::query();

        if (auth()->check()) {
            $query->where('user_id', auth()->id());
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%$search%")
                ->orWhere('description', 'like', "%$search%");
        }

        $categories = $query->withCount('products')->latest()->paginate(9);

        return view('categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryStoreRequest $request)
    {
        $validated = $request->validated();

        // Upload image if present
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $file = $request->file('image');
            $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))
                . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/categories'), $filename);
            $validated['image'] = $filename;
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['user_id'] = auth()->id();

        Category::create($validated);

        return redirect()->route('categories.index')->with('success', 'Category created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        if (auth()->check() && $category->user_id !== auth()->id()) {
            abort(403);
        }
        $category->load('products');
        return view('categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        if ($category->user_id !== auth()->id()) {
            abort(403);
        }
        return view('categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryUpdateRequest $request, Category $category)
    {
        $validated = $request->validated();

        // Upload new image and delete old one
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            if ($category->image && file_exists(public_path('images/categories/' . $category->image))) {
                unlink(public_path('images/categories/' . $category->image));
            }

            $file = $request->file('image');
            $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME))
                . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/categories'), $filename);
            $validated['image'] = $filename;
        }

        $validated['is_active'] = $request->has('is_active');

        $category->update($validated);

        return redirect()->route('categories.index')->with('success', 'Category updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        if ($category->user_id !== auth()->id()) {
            abort(403);
        }
        if ($category->image && file_exists(public_path('images/categories/' . $category->image))) {
            unlink(public_path('images/categories/' . $category->image));
        }

        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Category deleted successfully!');
    }

    /**
     * Display trashed categories.
     */
    public function trashed()
    {
        $categories = Category::onlyTrashed()->where('user_id', auth()->id())->latest()->paginate(9);
        return view('categories.trashed', compact('categories'));
    }

    /**
     * Restore a trashed category.
     */
    public function restore($id)
    {
        $category = Category::onlyTrashed()->where('user_id', auth()->id())->findOrFail($id);
        $category->restore();

        return redirect()->route('categories.trashed')->with('success', 'Category restored successfully!');
    }

    /**
     * Force delete a trashed category.
     */
    public function forceDelete($id)
    {
        $category = Category::onlyTrashed()->where('user_id', auth()->id())->findOrFail($id);

        if ($category->image && file_exists(public_path('images/categories/' . $category->image))) {
            unlink(public_path('images/categories/' . $category->image));
        }

        $category->forceDelete();

        return redirect()->route('categories.trashed')->with('success', 'Category permanently deleted!');
    }
}

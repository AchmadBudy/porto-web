<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\ListCategoryResource;
use App\Http\Resources\ShowCategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $categories = Category::query()
            ->latest()
            ->paginate(10);

        return Inertia::render('admin/category/Index', [
            'categories' => ListCategoryResource::collection($categories),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('admin/category/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
        ]);
        $data['slug'] = Str::slug($data['name']);

        // Create a new category
        Category::create($data);

        return to_route('admin.categories.index')->with('success', 'Category created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        $categor =  new ShowCategoryResource($category);
        return Inertia::render('admin/category/Show', [
            'category' => new ShowCategoryResource($category),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return Inertia::render('admin/category/Edit', [
            'currentCategory' => new ShowCategoryResource($category),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
        ]);
        $data['slug'] = Str::slug($data['name']);

        // Update the category
        $category->update($data);

        return to_route('admin.categories.index')->with('success', 'Category updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        // Delete the category
        $category->delete();

        return to_route('admin.categories.index')->with('success', 'Category deleted successfully');
    }
}

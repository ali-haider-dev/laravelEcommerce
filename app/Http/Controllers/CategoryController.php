<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // Retrieve all categories
    public function index()
    {
        // Fetches all categories, ordered by creation date
        $categories = Category::orderBy('created_at', 'desc')->with('products')->paginate(10);
        return view('categories.index', compact('categories'));
    }

    // Create a new category
    public function store(Request $request)
    {
        $request->validate([
            'category_name' => 'required|string|max:50|unique:tbl_categories,category_name',
        ]);

        $category = Category::create([
            'category_name' => $request->category_name,
            // Assuming you have middleware to set 'created_by' from the authenticated user
            'created_by' => auth()->id(),
        ]);

        return redirect()->back()->with([
            'success' => 'Category created successfully.',
        ]);
    }

    // Show a single category
    public function show(Category $category)
    {
        return response()->json($category);
    }

    public function filterCategory(Request $request)
    {
        $query = Category::query();

        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where('category_name', 'LIKE', '%' . $searchTerm . '%');
        }

        $categories = $query->orderBy('created_at', 'desc')->with('products')->paginate(10);

        return view('categories.index', compact('categories'));
    }

    // Update an existing category
    public function update(Request $request, Category $category)
    {



        $request->validate([
            'category_name' => 'required|string|max:50|unique:tbl_categories,category_name,' . $category->id,
        ]);


        $category->update([
            'category_name' => $request->category_name,
            'updated_by' => auth()->id(),
        ]);


        return redirect()->back()->with([
            'success' => 'Category updated successfully.',
        ]);
    }

    // Delete a category
    public function destroy(Category $category)
    {
        // Note: You should add logic here to prevent deletion if products are linked to it

        if ($category->products()->count() > 0) {
            return redirect()->back()->with([
                'error' => 'Cannot delete category with associated products.',
            ]);
        }
        $category->delete();

        return redirect()->back()->with([
            'success' => 'Category deleted successfully.',
        ]);
    }
}
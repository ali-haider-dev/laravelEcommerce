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
        $categories = Category::orderBy('created_at', 'desc')->paginate(10);
        return response()->json($categories);
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

        return response()->json([
            'message' => 'Category created successfully.', 
            'category' => $category
        ], 201);
    }

    // Show a single category
    public function show(Category $category)
    {
        return response()->json($category);
    }

    // Update an existing category
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'category_name' => 'required|string|max:50|unique:tbl_categories,category_name,' . $category->id,
        ]);

        $category->update([
            'category_name' => $request->category_name,
            'updated_by' => auth()->id(), // Update the auditor field
        ]);

        return response()->json([
            'message' => 'Category updated successfully.',
            'category' => $category
        ]);
    }

    // Delete a category
    public function destroy(Category $category)
    {
        // Note: You should add logic here to prevent deletion if products are linked to it
        $category->delete();
        
        return response()->json(null, 204);
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Retrieve all products with their category and creator, paginated.
     * Includes a filter for isActive.
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);

        $products = Product::with('category', 'creator')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return view('Product.index', compact('products'));
    }

    /**
     * Create a new product record.
     */
    public function store(Request $request)
    {
        $request->validate(rules: [
            'product_name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:tbl_categories,id',
            'attachments' => 'required|array|min:1',
            'attachments.*' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'isHot' => 'nullable|boolean',
            'isActive' => 'nullable|boolean',
        ]);

        
        $paths = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $paths[] = $file->store('products', 'public');
            }
        }

        $product = Product::create([
            'product_name' => $request->product_name,
            'description' => $request->description,
            'price' => $request->price,
            'category_id' => $request->category_id,
            'isHot' => $request->boolean('isHot'),
            'isActive' => $request->boolean('isActive', true),
            'attachments' => $paths,
            'created_by' => auth()->id(),
        ]);

        $product->load('category');

          return redirect()->back()->with('success', 'Product added successfully!');
    }

    /**
     * Display a single product.
     */
    // public function show(Product $product)
    // {
    //     $product->load('category', 'creator', 'editor');
    //     return response()->json($product);
    // }
    public function show()
    {
     
        $categories = Category::all();
        return view('Product.add_product', compact('categories'));
    }
    /**
     * Update an existing product.
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'product_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'nullable|exists:tbl_categories,id',
            'attachments.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'isHot' => 'nullable|boolean',
            'isActive' => 'nullable|boolean',
        ]);

        $existingAttachments = $product->attachments ?? [];

        // ✅ Handle deleted attachments (if passed as names/paths)
        if ($request->filled('deleted_attachments')) {
            $deletedFiles = explode(',', $request->deleted_attachments);
            foreach ($deletedFiles as $path) {
                $path = trim($path);
                Storage::disk('public')->delete($path);
                $existingAttachments = array_filter($existingAttachments, fn($file) => $file !== $path);
            }
        }

        // ✅ Handle newly uploaded attachments
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $existingAttachments[] = $file->store('products', 'public');
            }
        }

        $product->update([
            'product_name' => $request->input('product_name'),
            'description' => $request->input('description'),
            'price' => $request->input('price'),
            'category_id' => $request->input('category_id'),
            'isHot' => $request->boolean('isHot'),
            'isActive' => $request->boolean('isActive'),
            'attachments' => array_values($existingAttachments),
            'updated_by' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Product updated successfully!');
    }

    /**
     * Remove a product.
     */
    public function destroy(Product $product)
    {
        // ✅ Delete attached files from storage
        if ($product->attachments) {
            foreach ($product->attachments as $path) {
                Storage::disk('public')->delete($path);
            }
        }

        $product->delete();

      return  redirect()->back()->with('success', "product Added Successfully");
    }
}

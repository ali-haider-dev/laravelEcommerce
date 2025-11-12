<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserDashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $categories = Category::all();

        // // Fetch all products with their categories
        // $products = Product::with('category')->latest()->get();

        // // Group products by category_id
        // $groupedProducts = $products->groupBy('category_id');


        // if (Auth::check() && Auth::user()->designation == 'admin') {
        //     return redirect()->route('admin.dashboard');
        // }
        // // Send to view
        // return view('user.index', [
        //     'data' => [
        //         'categories' => $categories,
        //         'products' => $products,
        //         'grouped_products' => $groupedProducts
        //     ]
        // ]);

        // Check admin first
        if (Auth::check() && Auth::user()->designation === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        // Fetch categories with products
        $data['categories'] = Category::with([
            'products' => function ($query) {
                $query->latest(); // Latest products first
            }
        ])->get();

        // Send to view
        return view('user.index', compact('data'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

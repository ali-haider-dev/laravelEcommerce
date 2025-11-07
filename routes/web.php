<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
// use App\Http\Controllers\UserDashboardController;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

Route::get('admin/login', function () {
    // If user is already logged in
    if (Auth::check()) {
        $user = Auth::user();

        // Redirect based on role
        if (strtolower($user->designation) === 'admin') {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->route('user');
        }
    }

    // Otherwise, show login page
    return view('auth.login');
})->name('admin.login');
Route::get('admin/register', function () {
    // If user is already logged in
    if (Auth::check()) {
        $user = Auth::user();

        // Redirect based on role
        if (strtolower($user->designation) === 'admin') {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->route('user');
        }
    }

    // Otherwise, show login page
    return view('auth.register');
})->name('admin.register');

Route::get('/', function () {

    $categories = Category::all();

    // Fetch all products with their categories
    $products = Product::with('category')->latest()->get();

    // Group products by category_id
    $groupedProducts = $products->groupBy('category_id');


    if (Auth::check() && Auth::user()->designation == 'admin') {
        return redirect()->route('admin.dashboard');
    }
    // Send to view
    return view('user.index', [
        'data' => [
            'categories' => $categories,
            'products' => $products,
            'grouped_products' => $groupedProducts
        ]
    ]);

})->name('user');
// ====================================================================
// Group 1: Standard Authenticated Routes (Accessible to ALL logged-in users)
// ====================================================================
Route::middleware('auth')->group(function () {
    // NEW: Dashboard for all authenticated users (non-admin default view)
    // Profile routes for everyone
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

//========= Cart Routes =========================

Route::middleware(['auth'])->prefix('Cart')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('cart.index');
    Route::post('/', [CartController::class, 'store'])->name('cart.add');
    Route::get('/{cart}', [CartController::class, ''])->name('cart.get');
    Route::put('/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/{cart}', [CartController::class, 'destroy'])->name('cart.destroy');

});

//=============== Checkout Route ==================
Route::middleware(['auth'])->prefix('Checkout')->group(function () {
    Route::get('/', function () {
        $userId = Auth::id();

        $cartItems = Cart::where('user_id', $userId)
            ->with('product') 
            ->get();


        $cartTotal = $cartItems->sum(function ($item) {

            return $item->quantity * ($item->product->price ?? 0);
        });
        return view('user.checkout', [
            'cartItems' => $cartItems,
            'subtotal' => $cartTotal,
        ]);
    })->name('checkout');
     
    Route::post('/',[OrderController::class, 'store'])->name('checkout.store');
});
// ====================================================================
// Group 2: Administrator Only Routes (Requires 'auth' AND 'admin' middleware)
// ====================================================================
Route::middleware(['auth', 'admin'])->group(function () {

    // User Management (Admin Dashboard) Routes
    // FIX: Renamed 'dashboard' to 'admin.dashboard' to allow /dashboard for general users
    Route::get('/AdminDashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::delete('/AdminDashboard/{user}', [AdminController::class, 'destroy'])->name('dashboard.deleteuser');
    Route::get('/AdminDashboard/user', [AdminController::class, 'create'])->name('dashboard.getuser');
    Route::post('/AdminDashboard/user', [AdminController::class, 'store'])->name('dashboard.adduser');
    Route::get('/AdminDashboard/{user}/edit', [AdminController::class, 'edit'])->name('dashboard.edituser');
    Route::patch('/AdminDashboard/{user}', [AdminController::class, 'update'])->name('dashboard.updateuser');

    // ========================== Product & Reports Routes =================================
    Route::prefix('Products')->group(function () {

        // Product CRUD Routes (Admin-Only)
        Route::get('/', [ProductController::class, 'index'])->name('products');
        Route::get('/AddProduct', [ProductController::class, 'show'])->name('product.add');
        Route::post('/Addproduct', [ProductController::class, 'store'])->name('product.store');

        // Update/Delete Product
        Route::put('/{id}', [ProductController::class, 'update'])->name('product.update');
        Route::patch('/{id}', [ProductController::class, 'update'])->name('product.patch');
        Route::delete('/{product}', [ProductController::class, 'destroy'])->name('product.delete');

        // Reports Route (Admin-Only - accessible via /Products/reports)
        Route::get('/reports', [ReportController::class, 'showReport'])->name('reports.show');

    });
});

require __DIR__ . '/auth.php';

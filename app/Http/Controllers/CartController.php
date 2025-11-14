<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product; // Assuming the Product model is here
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response; // Using the standard Response for return hints
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;


class CartController extends Controller
{

    public function index(Request $request): JsonResponse|View
    {
        // Ensure the user is authenticated
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $userId = Auth::id();

        // Retrieve all cart items for the authenticated user, eagerly loading the related products
        $cartItems = Cart::where('user_id', $userId)
            ->with('product') // Load the product details using the relationship defined in the Cart model
            ->get();


        $cartTotal = $cartItems->sum(function ($item) {
            return $item->quantity * ($item->product->price ?? 0);
        });
        return view('user.cart', [
            'cartItems' => $cartItems,
            'subtotal' => $cartTotal,
        ]);


    }


    public function store(Request $request): JsonResponse|RedirectResponse
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }


        $validator = Validator::make($request->all(), [
            'product_id' => ['required', 'integer', Rule::exists(Product::class, 'id')],
            'quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {

            return redirect()->back()->with([
                'error' => 'Error adding Item to Cart',
            ]);

        }

        $userId = Auth::id();
        $productId = $request->product_id;
        $requestedQuantity = $request->input('quantity');

        // Check if the product already exists in the cart for the user
        $cartItem = Cart::where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        if ($cartItem) {
            // Item exists, increment the quantity
            $cartItem->quantity += $requestedQuantity;
            $cartItem->save();
            $message = 'Product quantity updated in cart.';
        } else {
            // Item does not exist, create a new cart entry
            $cartItem = Cart::create([
                'user_id' => $userId,
                'product_id' => $productId,
                'quantity' => $requestedQuantity,
            ]);
            $message = 'Product added to cart.';
        }
        $cartItem->load('product');
        return redirect()->back()->with([
            'success' => $message,
            'cart_item' => $cartItem,
        ]);

    }

    public function show(Cart $cart): Response|JsonResponse
    {
        // Ensure the user is authorized to view this cart item (optional, depending on business logic)
        if ($cart->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        return response()->json([
            'status' => '200',
            'message' => 'Retrieve successfully',
            'data' => $cart->load('product'),
        ], 200);
    }

    public function update(Request $request, $id): JsonResponse|RedirectResponse
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $validator = Validator::make($request->all(), [
            'quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with([
                'error' => 'Error adding Item to Cart',
            ]);
        }

        $userId = Auth::id();

        // Find the cart item, ensuring it belongs to the authenticated user
        $cartItem = Cart::where('id', $id)
            ->where('user_id', $userId)
            ->first();
        if (!$cartItem) {
            return redirect()->back()->with([
                'error' => 'Cart item not found or does not belong to user',
            ]);

        }

        // Update the quantity
        $cartItem->quantity = $request->input('quantity');
        $cartItem->save();

        $cartItem->load('product');
        return redirect()->back()->with([
            'success' => 'Cart item quantity updated.',
            'cart_item' => $cartItem,
        ]);
    }

    public function destroy(Request $request, $id): RedirectResponse|JsonResponse
{
    if (!Auth::check()) {
        return response()->json(['message' => 'Unauthenticated.'], 401);
    }

    $userId = Auth::id();

    $cartItem = Cart::where('id', $id)
        ->where('user_id', $userId)
        ->first();

    if (!$cartItem) {
        return redirect()->back()->with(['error' => 'Cart item not found.']);
    }

    $message = '';

    // Cast quantity to int
    $quantity = (int) $cartItem->quantity;

    if ($quantity === 1 || $request->has('delete')) {
        $cartItem->delete();
        $message = "Product successfully removed from cart.";
    } else {
        $cartItem->quantity -= 1;
        $cartItem->save();
        $message = "One unit of the product removed from the cart.";
    }

    return redirect()->back()->with(['success' => $message]);
}

}
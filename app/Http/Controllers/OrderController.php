<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    /**
     * Display a listing of the user's orders.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Retrieve all orders for the currently authenticated user, ordered by creation date
        $orders = Order::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    /**
     * Display the specified order.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show(Order $order)
    {
        // Policy check: Ensure the order belongs to the authenticated user
        if ($order->user_id !== Auth::id()) {
            return redirect()->route('orders.index')->with('error', 'You do not have permission to view that order.');
        }

        // Eager load items and their associated products
        $order->load('items.product');

        return view('orders.show', compact('order'));
    }

    /**
     * Place an order: move items from cart to order, and clear the cart.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request): RedirectResponse|JsonResponse|View
    {

        $userId = Auth::id();


        $data = $request->validate([
            'firstname' => 'required|string|max:100',
            'lastname' => 'required|string|max:100',
            'country' => 'required|string|max:50',
            'company' => 'nullable|string|max:255',
            'email' => ['required', 'string', 'email', 'exists:users,email'],
            'address1' => 'required|string|max:255',
            'address2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postcode' => 'required|string|max:20',
            'phone_number' => 'required|string|max:20',


            'shipping_address_string' => 'required|string|max:1000',
            'billing_address_string' => 'required|string|max:1000',

            'order_notes' => 'nullable|string|max:1000',
            'payment_method' => 'required|in:cod,paypal',


            'paypal_order_id' => 'required_if:payment_method,paypal|string|max:50',
        ]);
         $full_address_string = $request->address1 . $request->address2 .$request->city . $request->state;

        $cartItems = Cart::where('user_id', $userId)
            ->with('product') // Eager load product to get price
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty. Please add items before checking out.');
        }

        // Use a database transaction for reliability
        DB::beginTransaction();

        try {
            $totalAmount = 0;
            $orderNumber = 'ORD-' . time() . '-' . $userId;

            //  Create the main Order record
            $order = Order::create([
                'order_number' => $orderNumber,
                'user_id' => $userId,
                'total_amount' => 0,
                'payment_status' => 'pending',
                'order_status' => 'pending',
                'shipping_address' => $request->full_address_string,
                'billing_address' => $request->full_address_string,
                'payment_method' => $request->payment_method,
                'created_by' => $userId,
            ]);

            $orderItemsData = [];


            foreach ($cartItems as $cartItem) {
                $product = $cartItem->product;
                $price = $product->price;
                $subtotal = $price * $cartItem->quantity;
                $totalAmount += $subtotal;

                // Prepare data for the OrderItem
                $orderItemsData[] = new OrderItem([
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'unit_price' => $price, // Price at the time of purchase
                    'subtotal' => $subtotal,
                ]);

                // Optional: Decrement product stock (assuming a 'stock' column exists)
                // if ($product->stock >= $cartItem->quantity) {
                //     $product->stock -= $cartItem->quantity;
                //     $product->save();
                // } else {
                //     // Handle out of stock error (optional: rollback and inform user)
                //     DB::rollBack();
                //     return redirect()->route('cart.index')->with('error', 'One or more items in your cart went out of stock.');
                // }
            }

            //Save all Order Items at once
            $order->items()->saveMany($orderItemsData);

            //  Update the Order with the final total amount
            $order->total_amount = $request->total_amount;
            $order->save();

            //  Clear the user's cart
            Cart::where('user_id', $userId)->delete();

            // commit the transaction
            DB::commit();

            // redirect to confirmation view
            return view('user.order_confirm', [
                'order_number' => $orderNumber,
                "success" => 'Your order has been placed successfully! Order #' . $orderNumber
            ]);


        } catch (\Exception $e) {

            DB::rollBack();
            Log::error('Order Placement Error: ' . $e->getMessage(), ['user_id' => $userId]);
            return response()->json([
                'error' => $e->getMessage()
            ]);
            // Pass the exception message back to the user for debugging purposes
            // return redirect()->route('cart.index')
            //                  ->with('error', 'An unexpected error occurred while placing your order. Please try again. Error: ' . $e->getMessage());
        }
    }
}
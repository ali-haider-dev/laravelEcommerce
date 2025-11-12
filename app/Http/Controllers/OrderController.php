<?php

namespace App\Http\Controllers;

use App\Mail\OrderConfirmationMail;
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
use Illuminate\Support\Facades\Mail;

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
            'total_amount' => 'required|numeric|min:0',
            'shipping_cost' => 'required|numeric|min:0',
            'paypal_order_id' => 'required_if:payment_method,paypal|nullable|string|max:50',
        ]);

        // Build full address from individual fields for database storage
        $full_address_parts = array_filter([
            $request->address1,
            $request->address2,
            $request->city,
            $request->state,
            $request->postcode,
            $request->country
        ]);
        $full_address_string = implode(', ', $full_address_parts);

        $cartItems = Cart::where('user_id', $userId)
            ->with('product')
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Your cart is empty. Please add items before checking out.');
        }

        // Use a database transaction for reliability
        DB::beginTransaction();

        try {
            $calculatedTotal = 0;
            $orderNumber = 'ORD-' . time() . '-' . $userId;

            // Determine payment status based on payment method
            $paymentStatus = ($request->payment_method === 'paypal') ? 'paid' : 'pending';

            // Create the main Order record
            $order = Order::create([
                'order_number' => $orderNumber,
                'user_id' => $userId,
                'total_amount' => 0, // Will be updated after items are calculated
                'payment_status' => $paymentStatus,
                'order_status' => 'pending',
                'shipping_address' => $full_address_string,
                'billing_address' => $full_address_string,
                'payment_method' => $request->payment_method, // Dynamic: 'cod' or 'paypal'
                'order_notes' => $request->order_notes,
                'paypal_order_id' => $request->paypal_order_id, // Only populated for PayPal orders
                'created_by' => $userId,
            ]);

            $orderItemsData = [];

            foreach ($cartItems as $cartItem) {
                $product = $cartItem->product;

                // Check if product exists
                if (!$product) {
                    DB::rollBack();
                    return redirect()->route('cart.index')
                        ->with('error', 'One or more products in your cart no longer exist.');
                }

                $price = $product->price;
                $subtotal = $price * $cartItem->quantity;
                $calculatedTotal += $subtotal;

                // Prepare data for the OrderItem
                $orderItemsData[] = new OrderItem([
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'unit_price' => $price,
                    'subtotal' => $subtotal,
                ]);

                // Optional: Decrement product stock
                // if (isset($product->stock)) {
                //     if ($product->stock >= $cartItem->quantity) {
                //         $product->decrement('stock', $cartItem->quantity);
                //     } else {
                //         DB::rollBack();
                //         return redirect()->route('cart.index')
                //             ->with('error', "Product '{$product->product_name}' has insufficient stock.");
                //     }
                // }
            }

            // Save all Order Items at once
            $order->items()->saveMany($orderItemsData);

            // Add shipping cost to calculated total
            $finalTotal = $calculatedTotal + floatval($request->shipping_cost);

            // Verify the total matches what was submitted (prevent tampering)
            $submittedTotal = floatval($request->total_amount);
            if (abs($finalTotal - $submittedTotal) > 0.01) { // Allow for small rounding differences
                DB::rollBack();
                Log::warning('Order total mismatch', [
                    'user_id' => $userId,
                    'calculated' => $finalTotal,
                    'submitted' => $submittedTotal
                ]);
                return redirect()->route('cart.index')
                    ->with('error', 'Order total verification failed. Please try again.');
            }

            // Update the Order with the final total amount
            $order->total_amount = $finalTotal;
            $order->save();

            // Clear the user's cart
            Cart::where('user_id', $userId)->delete();

            // Commit the transaction
            DB::commit();

            // Send order confirmation email
            try {
                $user = Auth::user();
                Mail::to($user->email)->send(new OrderConfirmationMail($order));
            } catch (\Exception $mailException) {
                // Log email error but don't fail the order
                Log::error('Order confirmation email failed: ' . $mailException->getMessage(), [
                    'order_id' => $order->id,
                    'user_id' => $userId
                ]);
            }

            // Redirect to confirmation view
            return view('user.order_confirm', [
                'order_number' => $orderNumber,
                'order' => $order,
                'success' => 'Your order has been placed successfully! Order #' . $orderNumber
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Order Placement Error: ' . $e->getMessage(), [
                'user_id' => $userId,
                'trace' => $e->getTraceAsString()
            ]);
             return response()->json(['error' => 'An unexpected error occurred while placing your order. Please try again.'.$e->getMessage(),
            "fullerror"=>$e], 500);
            // return redirect()->route('cart.index')
            //     ->with('error', 'An unexpected error occurred while placing your order. Please try again.');
        }
    }
}
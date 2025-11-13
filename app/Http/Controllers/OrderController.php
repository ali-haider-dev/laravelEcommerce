<?php

namespace App\Http\Controllers;

use App\Mail\OrderConfirmationMail;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Stripe\Stripe; use Stripe\PaymentIntent;
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
    public function store(Request $request): RedirectResponse|View
    {


        try {
            $request->validate([
                'firstname' => 'required|string|max:255',
                'lastname' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'address1' => 'required|string|max:255',
                'city' => 'required|string|max:255',
                'state' => 'required|string|max:255',
                'postcode' => 'required|string|max:50',
                'phone_number' => 'required|string|max:50',
                'country' => 'required|string|max:255',
                'shipping_address' => 'required|string|max:1000',
                'billing_address' => 'required|string|max:1000',
                'total_amount' => 'required|numeric|min:0',
                // 'shipping_cost_amount' => 'required|numeric|min:0',
                'order_notes' => 'nullable|string|max:500'
            ]);
        } catch (ValidationException $e) {
            return back()->withInput()->withErrors($e->errors())
                ->with('order_error', 'Please correct the highlighted fields and try again.');
        }

        $request->merge(['payment_method' => 'cod']);

        return $this->processOrderCreation($request, 'pending');
    }

    public function storePayPal(Request $request): JsonResponse
    {
        try {
            // Validate all required fields
            $validated = $request->validate([
                'paypal_order_id' => 'required|string|max:255',
                'firstname' => 'required|string|max:255',
                'lastname' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'address1' => 'required|string|max:255',
                'city' => 'required|string|max:255',
                'state' => 'required|string|max:255',
                'postcode' => 'required|string|max:50',
                'phone_number' => 'required|string|max:50',
                'country' => 'required|string|max:255',
                'shipping_address' => 'required|string|max:1000',
                'billing_address' => 'required|string|max:1000',
                'total_amount' => 'required|numeric|min:0',
                // 'shipping_cost_amount' => 'required|numeric|min:0',
                'order_notes' => 'nullable|string|max:500'
            ]);

            Log::info('PayPal Order Request Received', [
                'user_id' => Auth::id(),
                'paypal_order_id' => $validated['paypal_order_id'],
                'total_amount' => $validated['total_amount']
            ]);
        } catch (ValidationException $e) {
            Log::warning('PayPal validation failed', ['errors' => $e->errors()]);
            return response()->json([
                'success' => false,
                'error' => 'Validation Failed',
                'messages' => $e->errors()
            ], 422);
        }

        $request->merge(['payment_method' => 'paypal']);

        try {
            $result = $this->processOrderCreation($request, 'paid');

            // Handle cart empty scenario
            if ($result instanceof RedirectResponse) {
                return response()->json([
                    'success' => false,
                    'error' => 'Your cart is empty. Please add items before checking out.'
                ], 400);
            }

            // Extract order number from the result
            $orderNumber = null;
            if ($result instanceof JsonResponse) {
                $data = $result->getData(true);
                $orderNumber = $data['orderNumber'] ?? null;
            }

            if (!$orderNumber) {
                throw new Exception('Order number not generated');
            }

            Log::info('PayPal Order Created Successfully', [
                'order_number' => $orderNumber,
                'user_id' => Auth::id()
            ]);

            // Return success with redirect URL
            return response()->json([
                'success' => true,
                'orderNumber' => $orderNumber,
                'redirect_url' => route('orders.confirmation', [
                    'orderNumber' => $orderNumber,
                    'success' => 'Order successfully placed!'
                ])
            ], 200);
        } catch (Exception $e) {
            Log::error('PayPal Order Creation Failed', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Checkout failed due to a system error. Please try again.',
                'details' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }


    private function processOrderCreation(Request $request, string $paymentStatus): View|RedirectResponse|JsonResponse
    {
        $userId = Auth::id();


        try {
            // Fetch cart items
            $cartItems = Cart::where('user_id', $userId)->with('product')->get();

            if ($cartItems->isEmpty()) {
                return redirect()->route('cart.index')
                    ->with('error', 'Your cart is empty. Please add items before checking out.');
            }

            DB::beginTransaction();

            $totalAmount = 0;
            $orderItemsData = [];

            // Process cart items
            foreach ($cartItems as $item) {
                $product = $item->product;

                if (!$product || $product->price <= 0) {
                    DB::rollBack();
                    throw new Exception("Product '{$product->product_name}' is unavailable or has an invalid price.");
                }

                $lockedPrice = $product->price;
                $totalAmount += ($lockedPrice * $item->quantity);

                $orderItemsData[] = new OrderItem([
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'unit_price' => $lockedPrice,
                ]);
            }

            // Verify total amount
            $requestedTotal = (float) $request->input('total_amount');
            $shippingCost = (float) $request->input('shipping_cost');
            $calculatedTotal = $totalAmount + $shippingCost;

            if (abs($requestedTotal - $calculatedTotal) > 0.01) {
                DB::rollBack();
                Log::error('Total amount mismatch', [
                    'user_id' => $userId,
                    'client_total' => $requestedTotal,
                    'server_total' => $calculatedTotal
                ]);
                throw new Exception('Order total mismatch. Please refresh and try again.');
            }

            // Create order
            $order = Order::create([
                'user_id' => $userId,
                'order_number' => 'ORD-' . time() . rand(100, 999),
                'total_amount' => $calculatedTotal,
                'payment_status' => $paymentStatus,
                'order_status' => 'processing',
                'shipping_address' => $request->input('shipping_address'),
                'billing_address' => $request->input('billing_address'),
                'payment_method' => $request->input('payment_method'),
                'transaction_id' => $request->input('paypal_order_id'),
                'created_by' => $userId,
            ]);

            // Create order items
            $order->items()->saveMany($orderItemsData);

            // Clear cart
            Cart::where('user_id', $userId)->delete();

            DB::commit();

            // Send confirmation email
            try {
                Mail::to(Auth::user()->email)->send(new OrderConfirmationMail($order));
            } catch (Exception $e) {
                Log::warning('Failed to send order confirmation email', [
                    'order_id' => $order->id,
                    'error' => $e->getMessage()
                ]);
            }

            // Return appropriate response based on payment method
            if ($request->input('payment_method') === 'cod') {
                return view('user.order_confirm', [
                    'orderNumber' => $order->order_number,
                    'success' => "Order #{$order->order_number} successfully placed!"
                ]);
            }

            // For PayPal: return JSON with order number
            return response()->json(['orderNumber' => $order->order_number], 200);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Order creation failed', [
                'user_id' => $userId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->input('payment_method') === 'cod') {
                return back()->withInput()
                    ->with('order_error', 'Checkout failed due to a system error. Please try again.');
            }

            throw $e;
        }
    }

    public function showConfirmation(Request $request): View
    {
        $orderNumber = $request->query('orderNumber');
        $successMessage = $request->query('success', 'Order successfully placed!');

        return view('user.order_confirm', [
            'orderNumber' => $orderNumber,
            'success' => $successMessage
        ]);
    }


    public function createStripeIntent(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'firstname' => 'required|string|max:255',
                'lastname' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'address1' => 'required|string|max:255',
                'city' => 'required|string|max:255',
                'state' => 'required|string|max:255',
                'postcode' => 'required|string|max:50',
                'phone_number' => 'required|string|max:50',
                'country' => 'required|string|max:255',
                'shipping_address' => 'required|string|max:1000',
                'billing_address' => 'required|string|max:1000',
                'total_amount' => 'required|numeric|min:0',
                'order_notes' => 'nullable|string|max:500'
            ]);

            Stripe::setApiKey(config('services.stripe.secret'));

            $totalAmount = (float) $request->input('total_amount');
            $userId = Auth::id();

            // Create Payment Intent
            $paymentIntent = PaymentIntent::create([
                'amount' => intval($totalAmount * 100), // Convert to cents
                'currency' => 'usd',
                'metadata' => [
                    'user_id' => $userId,
                    'user_email' => Auth::user()->email,
                    'order_method' => 'stripe'
                ],
                'description' => 'Molla Ecommerce Order'
            ]);

            Log::info('Stripe Payment Intent Created', [
                'user_id' => $userId,
                'intent_id' => $paymentIntent->id,
                'amount' => $totalAmount
            ]);

            return response()->json([
                'clientSecret' => $paymentIntent->client_secret
            ], 200);

        } catch (ValidationException $e) {
            Log::warning('Stripe validation failed', ['errors' => $e->errors()]);
            return response()->json([
                'success' => false,
                'error' => 'Validation Failed',
                'messages' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            Log::error('Stripe Payment Intent creation failed', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Payment initialization failed',
                'details' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Complete Stripe order after successful payment
     */
    public function storeStripe(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'stripe_payment_intent_id' => 'required|string|max:255',
                'firstname' => 'required|string|max:255',
                'lastname' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'address1' => 'required|string|max:255',
                'city' => 'required|string|max:255',
                'state' => 'required|string|max:255',
                'postcode' => 'required|string|max:50',
                'phone_number' => 'required|string|max:50',
                'country' => 'required|string|max:255',
                'shipping_address' => 'required|string|max:1000',
                'billing_address' => 'required|string|max:1000',
                'total_amount' => 'required|numeric|min:0',
                'order_notes' => 'nullable|string|max:500'
            ]);
            Stripe::setApiKey(config('services.stripe.secret'));

            // Verify payment intent
            $paymentIntent = PaymentIntent::retrieve(
                $validated['stripe_payment_intent_id']
            );

            if ($paymentIntent->status !== 'succeeded') {
                throw new Exception('Payment not completed');
            }

            Log::info('Stripe Payment Verified', [
                'user_id' => Auth::id(),
                'payment_intent_id' => $paymentIntent->id
            ]);

            $request->merge(['payment_method' => 'stripe']);

            // Create order using existing method
            $result = $this->processOrderCreation($request, 'paid');

            if ($result instanceof RedirectResponse) {
                return response()->json([
                    'success' => false,
                    'error' => 'Your cart is empty'
                ], 400);
            }

            $orderNumber = null;
            if ($result instanceof JsonResponse) {
                $data = $result->getData(true);
                $orderNumber = $data['orderNumber'] ?? null;
            }

            if (!$orderNumber) {
                throw new Exception('Order number not generated');
            }

            Log::info('Stripe Order Created', [
                'order_number' => $orderNumber,
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'success' => true,
                'orderNumber' => $orderNumber,
                'redirect_url' => route('orders.confirmation', [
                    'orderNumber' => $orderNumber,
                    'success' => 'Order successfully placed!'
                ])
            ], 200);

        } catch (ValidationException $e) {
            Log::warning('Stripe order validation failed', ['errors' => $e->errors()]);
            return response()->json([
                'success' => false,
                'error' => 'Validation Failed',
                'messages' => $e->errors()
            ], 422);
        } catch (Exception $e) {
            Log::error('Stripe order creation failed', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Order creation failed',
                'details' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
}
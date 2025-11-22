<?php

namespace App\Http\Controllers;

use App\Mail\OrderStatus;
use App\Models\Order;
use App\Models\User;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;
use Mail;
use Storage;
class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $users = User::paginate(10); // 10 users per page
        return view('dashboard.dashboard', compact('users'));


    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.add_user');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Input data aur file ko validate karna
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
            // File validation: Optional, max 5MB (5120 KB), accepted formats
            'file' => ['nullable', 'image', 'mimes:pdf,doc,docx,jpg,png', 'max:5120'],
            'designation' => ['required', 'string', 'max:255'],
        ]);

        try {
            // File path variable ko initialize karein
            $filePath = null;

            // Agar file request mein hai, toh use store karein
            if ($request->hasFile('file')) {
                // File ko 'public' disk mein 'user_uploads' folder mein store karna
                // Aur saved file ka path (user_uploads/filename.ext) $filePath mein store karna
                $filePath = $request->file('file')->store('user_uploads', 'public');
            }
            // 2. Naye user ko database mein create karna
            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                // Password ko secure tareeqe se hash (encrypt) karna zaroori hai
                'password' => Hash::make($validatedData['password']),
                // File ka rasta database column mein save karna
                "designation"=>$request->designation,
                'profile_document_path' => $filePath,
            ]);

            // 3. Status message ke saath dashboard par redirect karna
            return redirect()
                ->route('dashboard')
                ->with('success', 'user "' . $user->name . '" data updated successfully.');

        } catch (Exception $e) {
            // Agar koi error aata hai, to handle karna
            Log::error('User Store Error: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', 'error adding user.');
        }
    }

    /**
     * Display the specified resource.
     */

    public function filterOrder(Request $request)
    {
        $query = Order::with('user');

        // Search by order number or ID
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'LIKE', "%{$search}%")
                    ->orWhere('id', 'LIKE', "%{$search}%");
            });
        }

        // Filter by payment status
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // Filter by order status
        if ($request->filled('order_status')) {
            $query->where('order_status', $request->order_status);
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function updateOrderStatus(Request $request, Order $order)
    {


        $request->validate([
            'order_status' => 'required|in:pending,processing,shipped,delivered,cancelled'
        ]);

        $current = $order->order_status;
        $requested = $request->order_status;
        $allowedTransitions = [
            'pending' => ['processing', 'cancelled'],
            'processing' => ['shipped', 'cancelled'],
            'shipped' => ['delivered', 'cancelled'],
            'delivered' => [], // no backward or cancel allowed
            'cancelled' => [],
        ];
        if (!in_array($requested, $allowedTransitions[$current])) {
            return redirect()->back()->with('error', "You cannot change status from '$current' to '$requested'.");
        }
        if ($current === $requested) {
            return redirect()->back()->with('error', "The order is already in '$current' status.");
        }

        $order->update([
            'order_status' => $request->order_status,
            'updated_by' => Auth::id()
        ]);

        try {
            $user = User::find($order->user_id);

            // Send email notification to the user about the order status update
            Mail::to($user->email)->send(new OrderStatus($order));
            $message = ' An email notification has been sent to the customer.';
        } catch (Exception $e) {
            dd($e->getMessage());
            Log::error('Failed to send order status email: ' . $e->getMessage());
            // Optionally, you can choose to notify the admin about the email failure
            $message = ' However, failed to send email notification to the customer.';
        }

        return redirect()->back()->with('success', 'Order status updated successfully!' . $message);
    }
    public function showOrders()
    {
        //
        // Retrieve all orders ordered by creation date
        $orders = Order::orderBy('created_at', 'desc')->paginate(10);

        return view('orders.index', compact('orders'));
    }


    /**
     * Show the form for editing the specified resource.
     */

    public function edit(User $user)
    {
        // Returns the view for the user edit form, passing the $user object.
        return view('dashboard.update_user', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $fileField = 'file';

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,gif', 'max:5120'],
        ];
        $validated = $request->validate($rules);

        try {

            // Handle avatar upload
            if ($request->hasFile('file')) {
                if ($user->profile_document_path) {
                    Storage::disk('public')->delete($user->profile_document_path);
                }

                $path = $request->file('file')->store('user_uploads', 'public');
                $user->profile_document_path = $path;
            }

            // Update text fields
            $user->fill([
                'name' => $validated['name'],
                'email' => $validated['email'],
            ]);

            // Update password only if provided
            if (!empty($validated['password'])) {
                $user->password = Hash::make($validated['password']);
            }

            $user->save();

            return redirect()->route('dashboard')->with('success', '✅ User updated successfully!');
        } catch (Exception $e) {
            Log::error('User update failed: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', '⚠️ Error updating user:' . $e->getMessage());
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        try {
            // Perform the deletion
            $user->delete();

            // Return a successful JSON response
            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully.',
                'user_id' => $user->id
            ]);

        } catch (\Exception $e) {
            // Return an error JSON response
            return response()->json([
                'success' => false,
                'message' => 'Error deleting user: ' . $e->getMessage()
            ], 500); // 500 Internal Server Error
        }
    }
}

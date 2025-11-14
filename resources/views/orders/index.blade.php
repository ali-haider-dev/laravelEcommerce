<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="font-bold text-3xl text-gray-800">Orders Management</h1>
                <p class="text-sm text-gray-500 mt-1">Track and manage all customer orders</p>
            </div>
            <div class="hidden md:flex items-center space-x-2 text-sm text-gray-500">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>Last updated: {{ now()->format('M d, Y h:i A') }}</span>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            @include('components.success')

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                <!-- Total Orders -->
                <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-2xl shadow-lg p-6 text-white transform hover:scale-105 transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-indigo-100 text-sm font-medium mb-1">Total Orders</p>
                            <p class="text-4xl font-bold">{{ $orders->total() }}</p>
                            <p class="text-indigo-200 text-xs mt-2">All time</p>
                        </div>
                        <div class="bg-white/20 backdrop-blur-sm p-4 rounded-xl">
                            <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Pending Orders -->
                <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-2xl shadow-lg p-6 text-white transform hover:scale-105 transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-yellow-100 text-sm font-medium mb-1">Pending</p>
                            <p class="text-4xl font-bold">{{ $orders->where('order_status', 'pending')->count() }}</p>
                            <p class="text-yellow-200 text-xs mt-2">Awaiting action</p>
                        </div>
                        <div class="bg-white/20 backdrop-blur-sm p-4 rounded-xl">
                            <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Processing Orders -->
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-lg p-6 text-white transform hover:scale-105 transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-100 text-sm font-medium mb-1">Processing</p>
                            <p class="text-4xl font-bold">{{ $orders->where('order_status', 'processing')->count() }}</p>
                            <p class="text-blue-200 text-xs mt-2">In progress</p>
                        </div>
                        <div class="bg-white/20 backdrop-blur-sm p-4 rounded-xl">
                            <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Delivered Orders -->
                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl shadow-lg p-6 text-white transform hover:scale-105 transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-100 text-sm font-medium mb-1">Delivered</p>
                            <p class="text-4xl font-bold">{{ $orders->where('order_status', 'delivered')->count() }}</p>
                            <p class="text-green-200 text-xs mt-2">Completed</p>
                        </div>
                        <div class="bg-white/20 backdrop-blur-sm p-4 rounded-xl">
                            <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search and Filter Section -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-6 p-3">
                <div class="p-6">
                    <div class="flex items-center gap-2 mb-4">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-800">Filter & Search</h3>
                    </div>
                    
                    <form method="GET" action="{{ route('admin.searchOrders') }}" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                            <!-- Search Input -->
                            <div class="md:col-span-5">
                                <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Search Order</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                    </div>
                                    <input 
                                        type="text" 
                                        name="search" 
                                        id="search" 
                                        value="{{ request('search') }}"
                                        placeholder="Order number or ID..."
                                        class="block w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                    >
                                </div>
                            </div>

                            <!-- Payment Status Filter -->
                            <div class="md:col-span-3">
                                <label for="payment_status" class="block text-sm font-medium text-gray-700 mb-2">Payment Status</label>
                                <select 
                                    name="payment_status" 
                                    id="payment_status"
                                    class="block w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                >
                                    <option value="">All Payments</option>
                                    <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>💳 Pending</option>
                                    <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>✅ Paid</option>
                                    <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>❌ Failed</option>
                                    <option value="refunded" {{ request('payment_status') == 'refunded' ? 'selected' : '' }}>🔄 Refunded</option>
                                </select>
                            </div>

                            <!-- Order Status Filter -->
                            <div class="md:col-span-3">
                                <label for="order_status" class="block text-sm font-medium text-gray-700 mb-2">Order Status</label>
                                <select 
                                    name="order_status" 
                                    id="order_status"
                                    class="block w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                >
                                    <option value="">All Orders</option>
                                    <option value="pending" {{ request('order_status') == 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                                    <option value="processing" {{ request('order_status') == 'processing' ? 'selected' : '' }}>⚙️ Processing</option>
                                    <option value="shipped" {{ request('order_status') == 'shipped' ? 'selected' : '' }}>🚚 Shipped</option>
                                    <option value="delivered" {{ request('order_status') == 'delivered' ? 'selected' : '' }}>✅ Delivered</option>
                                    <option value="cancelled" {{ request('order_status') == 'cancelled' ? 'selected' : '' }}>❌ Cancelled</option>
                                </select>
                            </div>

                            <!-- Action Buttons -->
                            <div class="md:col-span-1 flex items-end gap-2">
                                <button 
                                    type="submit"
                                    class="w-full px-4 py-2.5 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 font-medium shadow-sm"
                                >
                                    <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </button>
                                <a 
                                    href="{{ route('admin.orders') }}"
                                    class="px-4 py-2.5 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200 font-medium"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Orders Table -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-800">All Orders</h3>
                    </div>
                    <span class="text-sm text-gray-500">{{ $orders->total() }} total orders</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Order Info</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Customer</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Amount</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Payment</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($orders as $order)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex flex-col">
                                            <span class="text-xs font-medium text-gray-500">#{{ $order->id }}</span>
                                            <span class="text-sm font-bold text-gray-900">{{ $order->order_number }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 rounded-full bg-gradient-to-br from-indigo-400 to-indigo-600 flex items-center justify-center text-white font-bold text-sm">
                                                {{ strtoupper(substr($order->user->name ?? 'U', 0, 2)) }}
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm font-medium text-gray-900">{{ $order->user->name ?? 'N/A' }}</p>
                                                <p class="text-xs text-gray-500">{{ $order->user->email ?? '' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-lg font-bold text-gray-900">Rs. {{ number_format($order->total_amount, 2) }}</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="space-y-1">
                                            @php
                                                $paymentColors = [
                                                    'pending' => 'bg-yellow-50 text-yellow-700 ring-yellow-600/20',
                                                    'paid' => 'bg-green-50 text-green-700 ring-green-600/20',
                                                    'failed' => 'bg-red-50 text-red-700 ring-red-600/20',
                                                    'refunded' => 'bg-purple-50 text-purple-700 ring-purple-600/20',
                                                ];
                                            @endphp
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold ring-1 ring-inset {{ $paymentColors[$order->payment_status] ?? 'bg-gray-50 text-gray-700 ring-gray-600/20' }}">
                                                {{ ucfirst($order->payment_status) }}
                                            </span>
                                            <div class="flex items-center gap-1 text-xs text-gray-500">
                                                @if($order->payment_method == 'cod')
                                                    <span>💵 Cash</span>
                                                @elseif($order->payment_method == 'paypal')
                                                    <span>🅿️ PayPal</span>
                                                @elseif($order->payment_method == 'stripe')
                                                    <span>💳 Card</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <form action="{{ route('admin.updateOrderStatus', $order->id) }}" method="POST" class="status-update-form">
                                            @csrf
                                            @method('PATCH')
                                            <select 
                                                name="order_status" 
                                                onchange="this.form.submit()"
                                                class="text-xs font-semibold rounded-lg px-3 py-2 border-0 ring-1 ring-inset focus:ring-2 transition-all cursor-pointer
                                                    {{ $order->order_status == 'pending' ? 'bg-yellow-50 text-yellow-700 ring-yellow-600/20 focus:ring-yellow-600' : '' }}
                                                    {{ $order->order_status == 'processing' ? 'bg-blue-50 text-blue-700 ring-blue-600/20 focus:ring-blue-600' : '' }}
                                                    {{ $order->order_status == 'shipped' ? 'bg-indigo-50 text-indigo-700 ring-indigo-600/20 focus:ring-indigo-600' : '' }}
                                                    {{ $order->order_status == 'delivered' ? 'bg-green-50 text-green-700 ring-green-600/20 focus:ring-green-600' : '' }}
                                                    {{ $order->order_status == 'cancelled' ? 'bg-red-50 text-red-700 ring-red-600/20 focus:ring-red-600' : '' }}"
                                            >
                                                <option value="pending" {{ $order->order_status == 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                                                <option value="processing" {{ $order->order_status == 'processing' ? 'selected' : '' }}>⚙️ Processing</option>
                                                <option value="shipped" {{ $order->order_status == 'shipped' ? 'selected' : '' }}>🚚 Shipped</option>
                                                <option value="delivered" {{ $order->order_status == 'delivered' ? 'selected' : '' }}>✅ Delivered</option>
                                                <option value="cancelled" {{ $order->order_status == 'cancelled' ? 'selected' : '' }}>❌ Cancelled</option>
                                            </select>
                                        </form>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex flex-col">
                                            <span class="text-sm font-medium text-gray-900">{{ $order->created_at->format('M d, Y') }}</span>
                                            <span class="text-xs text-gray-500">{{ $order->created_at->format('h:i A') }}</span>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-16 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="bg-gray-100 rounded-full p-6 mb-4">
                                                <svg class="h-16 w-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                                </svg>
                                            </div>
                                            <p class="text-lg font-semibold text-gray-900 mb-1">No orders found</p>
                                            <p class="text-sm text-gray-500">Try adjusting your search or filter criteria</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($orders->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                        {{ $orders->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        // Confirmation before status change with smooth animation
        document.querySelectorAll('.status-update-form select').forEach(select => {
            const originalValue = select.value;
            
            select.addEventListener('change', function(e) {
                const newValue = this.value;
                
                if (newValue === 'cancelled') {
                    if (!confirm('⚠️ Are you sure you want to cancel this order? This action cannot be undone.')) {
                        e.preventDefault();
                        this.value = originalValue;
                        return false;
                    }
                }
                
                // Add loading state
                this.disabled = true;
                this.style.opacity = '0.6';
                
                // Show loading indicator
                const loadingSpinner = document.createElement('div');
                loadingSpinner.className = 'inline-block ml-2 w-4 h-4 border-2 border-gray-300 border-t-indigo-600 rounded-full animate-spin';
                this.parentElement.appendChild(loadingSpinner);
            });
        });

        // Auto-hide success messages after 5 seconds
        const successMessages = document.querySelectorAll('[role="alert"]');
        successMessages.forEach(message => {
            if (message.textContent.includes('success')) {
                setTimeout(() => {
                    message.style.transition = 'opacity 0.5s ease-out';
                    message.style.opacity = '0';
                    setTimeout(() => message.remove(), 500);
                }, 5000);
            }
        });
    </script>
</x-app-layout>
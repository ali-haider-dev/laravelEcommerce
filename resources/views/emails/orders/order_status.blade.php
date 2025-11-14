<h2>Your Order Update</h2>

<p>Hello {{ $order->user->name ?? 'Customer' }},</p>

<p>Your order status has been updated.</p>

<p><strong>Order ID:</strong> {{ $order->order_number }}</p>
<p><strong>Status:</strong> {{ ucfirst($order->order_status) }}</p>

<p>Thank you for shopping with us!</p>

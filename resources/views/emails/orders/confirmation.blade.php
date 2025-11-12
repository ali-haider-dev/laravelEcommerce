<x-mail::message>
# Order Confirmation

Thank you for your order! Your order details are below.

**Order Number:** #{{ $order->order_number }}
**Order Date:** {{ $order->created_at->format('M d, Y') }}
**Total Amount:** ${{ number_format($order->total_amount, 2) }}

<x-mail::table>
| Product | Quantity | Price | Subtotal |
| :------------- | :----------: | :----------: | :------------: |
@foreach ($order->items as $item)
| {{ $item->product->product_name ?? 'N/A' }} | {{ $item->quantity }} | ${{ number_format($item->unit_price, 2) }} | ${{ number_format($item->subtotal, 2) }} |
@endforeach
</x-mail::table>

**Shipping Address:** {{ $order->shipping_address }}

**Payment Method:** {{ ucfirst($order->payment_method) }}

{{-- <x-mail::button :url="route('orders.show', $order->id)">
View My Order
</x-mail::button> --}}

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
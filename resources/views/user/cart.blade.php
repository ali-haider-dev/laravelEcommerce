<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Kahrido.pk - eCommerce </title>
    <meta name="keywords" content="HTML5 Template">
    <meta name="description" content="Molla - eCommerce">
    <meta name="author" content="p-themes">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/images/icons/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/images/icons/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/images/icons/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('assets/images/icons/site.html') }}">
    <link rel="mask-icon" href="{{ asset('assets/images/icons/safari-pinned-tab.svg') }}" color="#666666">
    <link rel="shortcut icon" href="{{ asset('assets/images/icons/favicon.ico') }}">
    <meta name="apple-mobile-web-app-title" content="Molla">
    <meta name="application-name" content="Molla">
    <meta name="msapplication-TileColor" content="#cc9966">
    <meta name="msapplication-config" content="{{ asset('assets/images/icons/browserconfig.xml') }}">
    <meta name="theme-color" content="#ffffff">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body>
    <div class="page-wrapper">

        @include('user.component.header')
        @include('user.component.toast')
        <main class="main">
            <div class="page-header text-center"
                style="background-image: url('{{ asset('storeAssets/images/page-header-bg.jpg') }}')">
                <div class="container">
                    <h1 class="page-title">Shopping Cart<span>Shop</span></h1>
                </div>
            </div>
            <nav aria-label="breadcrumb" class="breadcrumb-nav">
                <div class="container">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('/shop') }}">Shop</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Shopping Cart</li>
                    </ol>
                </div>
            </nav>
            <div class="page-content">
                <div class="cart">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-9">
                                <table class="table table-cart table-mobile">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Price</th>
                                            <th>Quantity</th>
                                            <th>Total</th>
                                            <th></th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        {{-- We now use $item->product->... to access the related data --}}
                                        @forelse ($cartItems as $item)
                                            @php
                                                // Access Product data through the 'product' relationship
                                                $product = $item->product ?? null;
                                                $price = $product['price'] ?? 0;
                                                $subtotal = $price * $item['quantity'];

                                                $attachments = $product['attachments'] ?? null;
                                                $product_name = $product['product_name'] ?? 'Missing Product';

                                            @endphp
                                            <tr>
                                                <td class="product-col">
                                                    <div class="product">
                                                        <figure class="product-media">
                                                            <a href="#">
                                                                <img src="{{ asset('storage/' . $attachments[0]) }}"
                                                                    alt="{{ $product_name }}" />
                                                            </a>
                                                        </figure>
                                                        <h3 class="product-title">
                                                            {{ $product_name }}
                                                        </h3>
                                                    </div>
                                                </td>

                                                <td class="price-col ">Rs. {{ number_format($price) }}</td>

                                                <td class="quantity-col p-4">
                                                    <div
                                                        class="flex items-center justify-center space-x-2 w-36 mx-auto bg-gray-100 rounded-lg shadow-inner border border-gray-200">

                                                        <form action="{{ route('cart.destroy', $item->id) }}"
                                                            method="POST" class="inline">
                                                            @csrf

                                                            @method('DELETE')


                                                            <button type="submit"
                                                                class="p-2 text-red-600 hover:text-red-700 transition duration-150 ease-in-out disabled:opacity-50"
                                                                aria-label="Decrease quantity or remove item">

                                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5"
                                                                    fill="none" viewBox="0 0 24 24"
                                                                    stroke="currentColor" stroke-width="2">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        d="M20 12H4" />
                                                                </svg>
                                                            </button>
                                                        </form>


                                                        <span
                                                            class="text-lg font-semibold text-gray-800 w-8 text-center"
                                                            aria-live="polite">
                                                            {{ $item['quantity'] }}
                                                        </span>


                                                        <form action="{{ route('cart.update', $item->id) }}"
                                                            method="POST" class="inline">
                                                            @csrf

                                                            @method('PUT')


                                                            <input type="hidden" name="quantity"
                                                                value="{{ $item['quantity'] + 1 }}">

                                                            <button type="submit"
                                                                class="p-2 text-green-600 hover:text-green-700 transition duration-150 ease-in-out"
                                                                aria-label="Increase quantity">

                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                    class="w-5 h-5" fill="none"
                                                                    viewBox="0 0 24 24" stroke="currentColor"
                                                                    stroke-width="2">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                                                </svg>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>

                                                <td class="total-col">Rs. {{ number_format($subtotal) }}</td>
                                                <td class="remove-col">
                                                    {{-- Note: Replace '#' with your actual remove route --}}
                                                    <a href="#" class="btn-remove"><i
                                                            class="icon-close"></i></a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center">Your cart is empty.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>

                                </table>
                                <div class="cart-bottom">
                                    <div class="cart-discount">
                                        {{-- In Laravel, the form action would be a route for applying a coupon --}}
                                        <form action="#" method="POST">
                                            @csrf
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="coupon_code"
                                                    required placeholder="coupon code">
                                                <div class="input-group-append">
                                                    <button class="btn btn-outline-primary-2" type="submit"><i
                                                            class="icon-long-arrow-right"></i></button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    {{-- The action would point to a Laravel route for updating the cart --}}
                                    <a href="#" class="btn btn-outline-dark-2"><span>UPDATE
                                            CART</span><i class="icon-refresh"></i></a>
                                </div>
                            </div>
                            <aside class="col-lg-3">
                                <div class="summary summary-cart">
                                    <h3 class="summary-title">Cart Total</h3>
                                    <table class="table table-summary">
                                        <tbody>
                                            @php
                                                // Calculate Subtotal dynamically from the passed $cartItems
                                                $cartSubtotal = 0;
                                                foreach ($cartItems as $item) {
                                                    $product = $item->product ?? null;
                                                    $price = $product['price'] ?? 0;
                                                    $cartSubtotal += $price * $item['quantity'];
                                                }
                                                // Initial Shipping Cost (default to Free Shipping: $0)
                                                $initialShipping = 0.0;
                                                $finalTotal = $cartSubtotal + $initialShipping;
                                            @endphp

                                            {{-- Subtotal Row --}}
                                            <tr class="summary-subtotal">
                                                <td>Subtotal:</td>
                                                <td>Rs. <span
                                                        id="cart-subtotal">{{ number_format($cartSubtotal, 2, '.', '') }}</span>
                                                </td>
                                            </tr>

                                            {{-- Shipping Options (Now with values and IDs) --}}
                                            <tr class="summary-shipping-row">
                                                <td>
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" id="free-shipping" name="shipping"
                                                            class="custom-control-input shipping-radio"
                                                            data-cost="0.00" value="0.00" checked>
                                                        <label class="custom-control-label" for="free-shipping">Free
                                                            Shipping</label>
                                                    </div>
                                                </td>
                                                <td class="shipping-cost-display">Rs. 0.00</td>
                                            </tr>

                                            <tr class="summary-shipping-row">
                                                <td>
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" id="standart-shipping" name="shipping"
                                                            class="custom-control-input shipping-radio"
                                                            data-cost="10.00" value="10.00">
                                                        <label class="custom-control-label"
                                                            for="standart-shipping">Standard:</label>
                                                    </div>
                                                </td>
                                                <td class="shipping-cost-display">Rs. 10.00</td>
                                            </tr>

                                            <tr class="summary-shipping-row">
                                                <td>
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" id="express-shipping" name="shipping"
                                                            class="custom-control-input shipping-radio"
                                                            data-cost="20.00" value="20.00">
                                                        <label class="custom-control-label"
                                                            for="express-shipping">Express:</label>
                                                    </div>
                                                </td>
                                                <td class="shipping-cost-display">Rs. 20.00</td>
                                            </tr>

                                            <tr class="summary-shipping-estimate">
                                                <td>Estimate for Your Country<br> <a href="#">Change address</a>
                                                </td>
                                                <td>&nbsp;</td>
                                            </tr>

                                            {{-- Final Total Row --}}
                                            <tr class="summary-total">
                                                <td>Total:</td>
                                                {{-- DYNAMICALLY UPDATED FINAL TOTAL --}}
                                                <td>Rs. <span
                                                        id="cart-final-total">{{ number_format($finalTotal, 2, '.', '') }}</span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>

                                    <a href="{{ route('checkout') }}" class="btn btn-outline-primary-2 btn-order btn-block">PROCEED TO
                                        CHECKOUT</a>
                                </div>
                                <a href="{{ url('/') }}"
                                    class="btn btn-outline-dark-2 btn-block mb-3"><span>CONTINUE SHOPPING</span><i
                                        class="icon-refresh"></i></a>
                            </aside>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        @include('user.component.footer')
    </div><button id="scroll-top" title="Back to Top"><i class="icon-arrow-up"></i></button>

    {{-- Mobile Menu --}}

    <div class="mobile-menu-overlay"></div>
    <div class="mobile-menu-container mobile-menu-light">
        <div class="mobile-menu-wrapper">
            <span class="mobile-menu-close"><i class="icon-close"></i></span>

            <form action="#" method="get" class="mobile-search">
                <label for="mobile-search" class="sr-only">Search</label>
                <input type="search" class="form-control" name="mobile-search" id="mobile-search"
                    placeholder="Search in..." required>
                <button class="btn btn-primary" type="submit"><i class="icon-search"></i></button>
            </form>

            <ul class="nav nav-pills-mobile nav-border-anim" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="mobile-menu-link" data-toggle="tab" href="#mobile-menu-tab"
                        role="tab" aria-controls="mobile-menu-tab" aria-selected="true">Menu</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="mobile-cats-link" data-toggle="tab" href="#mobile-cats-tab"
                        role="tab" aria-controls="mobile-cats-tab" aria-selected="false">Categories</a>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="mobile-menu-tab" role="tabpanel"
                    aria-labelledby="mobile-menu-link">
                    <nav class="mobile-nav">
                        <ul class="mobile-menu">
                            <li class="active">
                                <a href="index.html">Home</a>
                                <ul>
                                    <li><a href="index-1.html">01 - furniture store</a></li>
                                    <li><a href="index-2.html">02 - furniture store</a></li>
                                    {{-- ... more links ... --}}
                                </ul>
                            </li>
                            <li><a href="category.html">Shop</a></li>
                            <li><a href="product.html" class="sf-with-ul">Product</a></li>
                            <li><a href="#">Pages</a></li>
                            <li><a href="blog.html">Blog</a></li>
                            <li><a href="elements-list.html">Elements</a></li>
                        </ul>
                    </nav>
                </div>
                {{-- <div class="tab-pane fade" id="mobile-cats-tab" role="tabpanel" aria-labelledby="mobile-cats-link">
                    <nav class="mobile-cats-nav">
                        <ul class="mobile-cats-menu">
                            @foreach ($categories as $category)
                                <li><a
                                        href="category.html?id={{ $category['id'] }}">{{ $category['category_name'] }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </nav>
                </div> --}}
            </div>

            <div class="social-icons">
                <a href="#" class="social-icon" target="_blank" title="Facebook"><i
                        class="icon-facebook-f"></i></a>
                <a href="#" class="social-icon" target="_blank" title="Twitter"><i
                        class="icon-twitter"></i></a>
                <a href="#" class="social-icon" target="_blank" title="Instagram"><i
                        class="icon-instagram"></i></a>
                <a href="#" class="social-icon" target="_blank" title="Youtube"><i
                        class="icon-youtube"></i></a>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.hoverIntent.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.waypoints.min.js') }}"></script>
    <script src="{{ asset('assets/js/superfish.min.js') }}"></script>
    <script src="{{ asset('assets/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-input-spinner.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const shippingRadios = document.querySelectorAll('.shipping-radio');
            const subtotalElement = document.getElementById('cart-subtotal');
            const finalTotalElement = document.getElementById('cart-final-total');

            if (!subtotalElement || !finalTotalElement) {
                console.error('Cart total elements not found.');
                return;
            }

            // Function to calculate and update the total
            function updateCartTotal() {
                // Get subtotal (remove commas, parse as float)
                let subtotalText = subtotalElement.textContent.replace(/,/g, '');
                let subtotal = parseFloat(subtotalText);

                if (isNaN(subtotal)) {
                    console.error('Subtotal value is invalid.');
                    return;
                }

                // Find the currently selected shipping cost
                let selectedShippingCost = 0;
                shippingRadios.forEach(radio => {
                    if (radio.checked) {
                        // Get the cost from the data-cost attribute
                        selectedShippingCost = parseFloat(radio.getAttribute('data-cost'));
                    }
                });

                // Calculate new total
                let newTotal = subtotal + selectedShippingCost;

                // Update the final total display
                finalTotalElement.textContent = newTotal.toFixed(2);
            }

            // Add event listeners to all shipping radios
            shippingRadios.forEach(radio => {
                radio.addEventListener('change', updateCartTotal);
            });

            // Run once on load to ensure the initial total is correct (though it should be from the PHP)
            updateCartTotal();
        });
    </script>
</body>

</html>

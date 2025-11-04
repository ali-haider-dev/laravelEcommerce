@php
    // --- STATIC DUMMY DATA FOR FRONT-END PRESENTATION ---
    // In a real Laravel application, this data would be passed from a controller.

    // 1. Static Categories (for the dropdown menu)
    $all_categories = [
        ['id' => 10, 'category_name' => 'Electronics'],
        ['id' => 11, 'category_name' => 'Appliances'],
        ['id' => 12, 'category_name' => 'Digital Cameras'],
        ['id' => 13, 'category_name' => 'Cell Phones & Tablets'],
        ['id' => 14, 'category_name' => 'Smart Home'],
        ['id' => 15, 'category_name' => 'Audio & Video'],
        ['id' => 16, 'category_name' => 'Gaming Consoles'],
    ];

    // 2. Static Cart Items (for the dropdown mini-cart)
    // NOTE: In a static page, we simulate 2 items in the cart
    $cart_items = [
        [
            'product_id' => 101,
            'product_name' => 'High-Resolution Monitor',
            'price' => 350.0,
            'quantity' => 1,
            'image' => 'products/product-1.jpg', // Dummy image path
        ],
        [
            'product_id' => 205,
            'product_name' => 'Wireless Mechanical Keyboard',
            'price' => 120.0,
            'quantity' => 2,
            'image' => 'products/product-2.jpg',
        ],
    ];

    // 3. Static Cart Totals and User Status
    $cart_total = array_sum(
        array_map(function ($item) {
            return $item['price'] * $item['quantity'];
        }, $cart_items),
    );
    $cart_count = array_sum(array_column($cart_items, 'quantity')); // Total quantity of items

    // Simulation for user status
    $user_is_logged_in = true; // Set to false to see the Sign in/Sign up link
    $user_designation = 'user'; // 'user' or 'admin' or null
    $user_name = 'John Doe';
@endphp

@vite(['resources/css/app.css', 'resources/js/app.js'])



<header class="header header-intro-clearance header-4 border-b border-gray-200">
    {{-- Top Header Section with Tailwind classes --}}
    <div class="header-top bg-gray-100 py-2">
        <div class="container mx-auto px-4 flex justify-between items-center">
            <div class="header-left">
                <a href="tel:#" class="text-xl text-gray-600 hover:text-blue-500 transition duration-300">
                    <i class="icon-phone mr-1"></i>Call: **+0123 456 789**
                </a>
            </div>

            <div class="header-right">
                <ul class="top-menu flex space-x-4">
                    <li class="relative">
                        <a href="#"
                            class="text-2xl text-gray-600 hover:text-blue-500 transition duration-300">Links</a>
                        {{-- Dropdowns are kept simple, using existing HTML structure/classes --}}
                        <ul>
                            <li>
                                <div class="header-dropdown">
                                    <a href="#">USD</a>
                                    <div class="header-menu">
                                        <ul>
                                            <li><a href="#">Eur</a></li>
                                            <li><a href="#">Usd</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="header-dropdown">
                                    <a href="#">English</a>
                                    <div class="header-menu">
                                        <ul>
                                            <li><a href="#">English</a></li>
                                            <li><a href="#">French</a></li>
                                            <li><a href="#">Spanish</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </li>
                            @guest
                                <li><a href="#signin-modal" data-toggle="modal"
                                        class="text-xl font-medium hover:text-red-500">Sign in / Sign up</a></li>
                            @endguest
                            @auth


                                <li class="relative">
                                    <div class="header-dropdown">
                                        <a href="#">Hello,
                                            {{ Auth::user()->name }}</a>
                                        <div class="header-menu">
                                            <ul>


                                                <li><a href="orders.php">Orders</a></li>
                                                <li><a href="profile.php">Profile</a></li>
                                                <form method="POST" action="{{ route('logout') }}">
                                                    @csrf

                                                    <x-responsive-nav-link :href="route('logout')"
                                                    @class('text-3xl')
                                                        onclick="event.preventDefault();
                                        this.closest('form').submit();">
                                                        {{ __('Log Out') }}
                                                    </x-responsive-nav-link>
                                                </form>

                                            </ul>
                                        </div>
                                    </div>
                                </li>
                            @endauth
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Middle Header Section with Tailwind classes --}}
    <div class="header-middle py-6">
        <div class="container mx-auto px-4 flex justify-between items-center gap-20">
            <div class="header-left flex items-center">

                <a href="index.php" class="logo">
                    <img src={{ asset('assets/images/demos/demo-4/logo.png') }} alt="Molla Logo" width="105"
                        height="25">
                </a>
            </div>

            {{-- <div class="header-center flex-grow mx-12">
                <div class="header-search header-search-extended header-search-visible">
                   
                    <form action="#" method="get">
                        <div
                            class="header-search-wrapper search-wrapper-wide flex items-center border rounded-full overflow-hidden">
                            <label for="q" class="sr-only">Search</label>
                            
                            <button class="btn btn-primary bg-transparent p-2" type="submit">
                                <i class="icon-search text-4xl text-blue-500"></i>
                            </button>
                            <input type="search" class="form-control flex-grow border-none focus:ring-0 px-3 py-2"
                                name="q" id="q" placeholder="Search product ..." required>
                        </div>
                    </form>
                </div>
            </div> --}}

            <div class="header-right flex items-center space-x-6">


                {{-- Wishlist (Static) --}}
                <div class="wishlist hidden md:block">
                    <a href="wishlist.html" title="Wishlist">
                        <div class="icon">
                            <i class="icon-heart-o"></i>
                            <span class="wishlist-count badge">3</span>
                        </div>
                        <p>Wishlist</p>
                    </a>
                </div>

                {{-- Cart Dropdown (Uses Static Cart Data) --}}
                <div class="dropdown cart-dropdown">
                    <a href="#" class="dropdown-toggle" role="button" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false" data-display="static">
                        <div class="icon">
                            <i class="icon-shopping-cart"></i>
                            {{-- Blade output for static count --}}
                            <span class="cart-count">5</span>
                        </div>
                        <p>Cart</p>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right">
                        <div class="dropdown-cart-products">
                            {{-- Blade @if/@foreach structure to loop over static cart items --}}
                            @if (!empty($cart_items))
                                @foreach ($cart_items as $item)
                                    <div class="product">
                                        <div class="product-cart-details">
                                            <h4 class="product-title">
                                                <a href="product.php?id={{ $item['product_id'] }}">
                                                    {{ htmlspecialchars($item['product_name']) }}
                                                </a>
                                            </h4>
                                            <span class="cart-product-info">
                                                <span class="cart-product-qty">{{ $item['quantity'] }}</span>
                                                x ${{ number_format($item['price'], 2) }}
                                            </span>
                                        </div>
                                        <figure class="product-image-container">
                                            <a href="product.php?id={{ $item['product_id'] }}" class="product-image">
                                                <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQCvHwovbRHB9NnFG6PaeXbFZMAczyZ6m9EHQ&s"
                                                    alt="product">
                                            </a>
                                        </figure>
                                        {{-- Note: The `cart.php?remove=` link is kept static for navigation only --}}
                                        <a href="cart.php?remove={{ $item['product_id'] }}" class="btn-remove"
                                            title="Remove Product">
                                            <i class="icon-close"></i>
                                        </a>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-center p-3 text-gray-500">Your cart is empty</p>
                            @endif
                        </div>

                        {{-- Cart total and action buttons (Uses Static Cart Data) --}}
                        @if (!empty($cart_items))
                            <div class="dropdown-cart-total">
                                <span>Total</span>
                                <span class="cart-total-price">${{ number_format($cart_total, 2) }}</span>
                            </div>

                            <div class="dropdown-cart-action">
                                <a href="cart.php" class="btn btn-primary">View Cart</a>
                                <a href="checkout.php" class="btn btn-outline-primary-2">
                                    <span>Checkout</span><i class="icon-long-arrow-right"></i>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>


</header>

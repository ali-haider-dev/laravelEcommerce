@props(['allCategories' => [], 'cartItems' => [], 'cartTotal' => 0, 'cartCount' => 0])

<header class="w-full shadow-md">
    <div class="bg-gray-100 py-2 border-b border-gray-200">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center text-sm">
            <div class="flex items-center space-x-2">
                <i class="fas fa-phone mr-1"></i>
                <a href="tel:#" class="text-gray-700 hover:text-indigo-600">Call: +0123 456 789</a>
            </div>

            <div class="flex items-center space-x-4">
                <ul class="flex space-x-4">
                    {{-- Currency Dropdown --}}
                    <li class="relative group">
                        <a href="#" class="hover:text-indigo-600">USD</a>
                        <ul class="absolute right-0 mt-2 w-24 bg-white border border-gray-200 rounded-md shadow-lg hidden group-hover:block z-20">
                            <li><a href="#" class="block px-3 py-1 hover:bg-gray-100">EUR</a></li>
                            <li><a href="#" class="block px-3 py-1 hover:bg-gray-100">USD</a></li>
                        </ul>
                    </li>

                    {{-- Language Dropdown --}}
                    <li class="relative group">
                        <a href="#" class="hover:text-indigo-600">English</a>
                        <ul class="absolute right-0 mt-2 w-24 bg-white border border-gray-200 rounded-md shadow-lg hidden group-hover:block z-20">
                            <li><a href="#" class="block px-3 py-1 hover:bg-gray-100">English</a></li>
                            <li><a href="#" class="block px-3 py-1 hover:bg-gray-100">French</a></li>
                            <li><a href="#" class="block px-3 py-1 hover:bg-gray-100">Spanish</a></li>
                        </ul>
                    </li>

                    {{-- Sign In / Sign Up --}}
                    @guest
                        <li>
                            <a href="#" @click.prevent="$dispatch('signin-modal')" class="hover:text-indigo-600">
                                Sign In / Sign Up
                            </a>
                        </li>
                    @endguest

                    {{-- My Account Dropdown --}}
                    @auth
                        <li class="relative group">
                            <a href="#" class="hover:text-indigo-600">My Account</a>
                            <ul class="absolute right-0 mt-2 w-32 bg-white border border-gray-200 rounded-md shadow-lg hidden group-hover:block z-20">
                                <li>
                                    <a href="{{ route('profile.edit') }}" class="block px-3 py-1 hover:bg-gray-100">Profile</a>
                                </li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full text-left px-3 py-1 hover:bg-gray-100">Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </div>

    <div class="py-4">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center">
            {{-- Logo --}}
            <div class="flex items-center">
                <button class="lg:hidden p-2 text-gray-600 hover:text-gray-900">
                    <i class="fas fa-bars"></i>
                </button>
                <a href="{{ url('/') }}" class="ml-4 lg:ml-0">
                    <img src="{{ asset('assets/images/demos/demo-4/logo.png') }}" alt="Molla Logo" class="w-28 h-auto">
                </a>
            </div>

            {{-- Right Side Icons --}}
            <div class="flex items-center space-x-6">
                {{-- Wishlist --}}
                <a href="#" title="Wishlist" class="text-gray-600 hover:text-indigo-600 flex flex-col items-center">
                    <div class="relative">
                        <i class="far fa-heart text-2xl"></i>
                        <span class="absolute -top-1 -right-2 px-2 py-0.5 text-xs font-bold text-white bg-red-600 rounded-full">
                            3
                        </span>
                    </div>
                    <p class="text-xs mt-1">Wishlist</p>
                </a>

                {{-- Cart --}}
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" class="text-gray-600 hover:text-indigo-600 flex flex-col items-center">
                        <div class="relative">
                            <i class="fas fa-shopping-cart text-2xl"></i>
                            <span class="absolute -top-1 -right-2 px-2 py-0.5 text-xs font-bold text-white bg-indigo-600 rounded-full">
                                {{ $cartCount }}
                            </span>
                        </div>
                        <p class="text-xs mt-1">Cart</p>
                    </button>

                    {{-- Cart Dropdown --}}
                    <div x-show="open" @click.away="open = false" x-transition
                        class="absolute right-0 mt-3 w-72 bg-white border border-gray-200 rounded-lg shadow-xl z-30">
                        <div class="p-3 space-y-3">
                            @if (!empty($cartItems))
                                @foreach ($cartItems as $item)
                                    <div class="flex items-center border-b pb-3 last:border-b-0">
                                        <figure class="w-16 h-16 mr-3 flex-shrink-0">
                                            <a href="{{ route('product', ['id' => $item['product_id']]) }}">
                                                <img src="{{ asset('admin/' . e($item['image'])) }}" alt="product"
                                                     class="object-cover w-full h-full rounded">
                                            </a>
                                        </figure>
                                        <div class="flex-grow">
                                            <h4 class="text-sm font-medium">
                                                <a href="{{ route('product', ['id' => $item['product_id']]) }}"
                                                   class="hover:text-indigo-600">{{ e($item['product_name']) }}</a>
                                            </h4>
                                            <span class="text-xs text-gray-500">
                                                <span class="font-semibold">{{ $item['quantity'] }}</span> ×
                                                ${{ number_format($item['price'], 2) }}
                                            </span>
                                        </div>
                                        <a href="{{ route('remove_from_cart', ['id' => $item['product_id']]) }}"
                                           class="text-gray-400 hover:text-red-500 ml-2" title="Remove Product">
                                            <i class="fas fa-times"></i>
                                        </a>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-center text-gray-500 p-2">Your cart is empty</p>
                            @endif
                        </div>

                        @if (!empty($cartItems))
                            <div class="border-t pt-3 px-3">
                                <div class="flex justify-between font-semibold mb-3">
                                    <span>Total</span>
                                    <span class="text-indigo-600">${{ number_format($cartTotal, 2) }}</span>
                                </div>
                                <div class="flex justify-between space-x-2 pb-3">
                                    <a href="{{ route('cart') }}" class="flex-1 text-center py-2 text-white bg-indigo-600 rounded hover:bg-indigo-700">
                                        View Cart
                                    </a>
                                    <a href="{{ route('checkout') }}" class="flex-1 text-center py-2 text-indigo-600 border border-indigo-600 rounded hover:bg-indigo-50">
                                        Checkout <i class="fas fa-arrow-right ml-1"></i>
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

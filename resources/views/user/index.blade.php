@php
    function format_price($price)
    {
        return '$' . number_format($price, 2);
    }

    function get_rating_width()
    {
        return rand(60, 100);
    }

    function get_review_count()
    {
        return rand(2, 12);
    }

    // --- Generate tab structure dynamically ---
    $tabs = [
        'new-all-tab' => ($allProducts = $data['categories']->flatMap(function ($category) {
            return $category->products;
        })),
    ];
    foreach ($data['categories'] as $category) {
        $key = 'new-' . strtolower(preg_replace('/[^a-z0-9]+/', '-', $category->category_name)) . '-tab';
        $tabs[$key] = $category->products; // directly use the products relation
    }

@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Ecommerce</title>
    <link rel="stylesheet"
        href={{ asset('assets/vendor/line-awesome/line-awesome/line-awesome/css/line-awesome.min.css') }}>
    <link rel="stylesheet" href={{ asset('assets/css/bootstrap.min.css') }}>
    <link rel="stylesheet" href={{ asset('assets/css/plugins/owl-carousel/owl.carousel.css') }}>
    <link rel="stylesheet" href={{ asset('assets/css/plugins/magnific-popup/magnific-popup.css') }}>
    <link rel="stylesheet" href={{ asset('assets/css/plugins/jquery.countdown.css') }}>
    <link rel="stylesheet" href={{ asset('assets/css/style.css') }}>
    <link rel="stylesheet" href={{ asset('assets/css/skins/skin-demo-4.css') }}>
    <link rel="stylesheet" href={{ asset('assets/css/demos/demo-4.css') }}>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>

    <div class="page-wrapper min-h-screen bg-gray-50">
        @include('user.component.header')

        @include('user.component.toast')

        <main class="main">
            {{-- Introductory Slider Section --}}
            <div class="intro-slider-container mb-5">
                <div class="intro-slider owl-carousel owl-theme owl-nav-inside owl-light" data-toggle="owl"
                    data-owl-options='{"dots": true, "nav": false, "responsive": {"1200": {"nav": true, "dots": false}}}'>
                    <div class="intro-slide"
                        style="background-image: url(assets/images/demos/demo-4/slider/slide-1.png);">
                        <div class="container intro-content">
                            <div class="row justify-content-end">
                                <div class="col-auto col-sm-7 col-md-6 col-lg-5">
                                    <h3 class="intro-subtitle text-third">Deals and Promotions</h3>
                                    <h1 class="intro-title">Beats by</h1>
                                    <h1 class="intro-title">Dre Studio 3</h1>
                                    <div class="intro-price">
                                        <sup class="intro-old-price">$349,95</sup>
                                        <span class="text-third">$279<sup>.99</sup></span>
                                    </div>
                                    <a href="category.html" class="btn btn-primary btn-round">
                                        <span>Shop More</span>
                                        <i class="icon-long-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="intro-slide"
                        style="background-image: url(assets/images/demos/demo-4/slider/slide-2.png);">
                        <div class="container intro-content">
                            <div class="row justify-content-end">
                                <div class="col-auto col-sm-7 col-md-6 col-lg-5">
                                    <h3 class="intro-subtitle text-primary">New Arrival</h3>
                                    <h1 class="intro-title">Apple iPad Pro <br>12.9 Inch, 64GB </h1>
                                    <div class="intro-price">
                                        <sup>Today:</sup>
                                        <span class="text-primary">$999<sup>.99</sup></span>
                                    </div>
                                    <a href="category.html" class="btn btn-primary btn-round">
                                        <span>Shop More</span>
                                        <i class="icon-long-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <span class="slider-loader"></span>
            </div>

            <div class="container">
                <h2 class="title text-center mb-4 text-6xl font-bold text-gray-800">Explore Popular Categories</h2>

                {{-- Category Blocks (Static Content from PHP Loop) --}}
                <div class="cat-blocks-container">
                    <div class="row">

                        @if (count($data['categories']) > 0)
                            @foreach ($data['categories'] as $category)
                                @php
                                    $safe_category_name = $category['category_name'];
                                    // Use a modulo operation to cycle through the dummy images
                                    $image_index = ($loop->index % 8) + 1; // Cycle 1 to 8
                                    $image_src = 'assets/images/demos/demo-4/cats/' . $image_index . '.png';
                                    $category_link = 'category.html?id=' . $category['id'];
                                @endphp

                                <div class="col-6 col-sm-4 col-lg-2">
                                    <a href="{{ $category_link }}" class="cat-block transition duration-300 p-5 ">
                                        <figure>
                                            <span>
                                                <img src="{{ $image_src }}" alt="{{ $safe_category_name }} image">
                                            </span>
                                        </figure>
                                        <h3 class="cat-block-title">{{ $safe_category_name }}</h3>
                                    </a>
                                </div>
                            @endforeach
                        @else
                            <div class="col-12">
                                <p>No categories found.</p>
                            </div>
                        @endif

                    </div>
                </div>
                <div class="mb-3"></div>

                {{-- New Arrivals Products Section (Static Content from PHP/Database Loop) --}}
                <div class="container new-arrivals">
                    <div class="heading heading-flex mb-3">
                        <div class="heading-left">
                            <h2 class="title text-2xl font-semibold">New Arrivals</h2>
                        </div>
                        <div class="heading-right">
                            <ul class="nav nav-pills nav-border-anim justify-content-center" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="new-all-link" data-toggle="tab" href="#new-all-tab"
                                        role="tab" aria-controls="new-all-tab" aria-selected="true">All</a>
                                </li>
                                @foreach ($data['categories'] as $category)
                                    @php
                                        $tabId =
                                            'new-' .
                                            strtolower(preg_replace('/[^a-z0-9]+/', '-', $category->category_name)) .
                                            '-tab';

                                    @endphp

                                    <li class="nav-item">
                                        <a class="nav-link" id="{{ $tabId }}-link" data-toggle="tab"
                                            href="#{{ $tabId }}" role="tab"
                                            aria-controls="{{ $tabId }}" aria-selected="false">
                                            {{ $category->category_name }}
                                        </a>
                                    </li>
                                @endforeach



                            </ul>
                        </div>
                    </div>

                    <div class="tab-content tab-content-carousel just-action-icons-sm">

                        @php $first_tab = true; @endphp

                        @foreach ($tabs as $tab_id => $products)
                            @php
                                $is_active = $first_tab ? 'show active' : 'fade';
                                $first_tab = false;
                            @endphp

                            <div class="tab-pane p-0 {{ $is_active }}" id="{{ $tab_id }}" role="tabpanel"
                                aria-labelledby="{{ $tab_id . '-link' }}">

                                <div class="owl-carousel owl-full carousel-equal-height carousel-with-shadow"
                                    data-toggle="owl"
                                    data-owl-options='{"nav": true, "dots": true, "margin": 20, "loop": false, "responsive": {"0": {"items":2}, "480": {"items":2}, "768": {"items":3}, "992": {"items":4}, "1200": {"items":5}}}'>

                                    @forelse ($products as $product)
                                        @php
                                            // Labels
                                            $labels = '';
                                            if ($product->isHot ?? false) {
                                                $labels .=
                                                    '<span class="product-label label-circle label-top">Hot</span>';
                                            }
                                            if ($product->created_at->gt(\Carbon\Carbon::now()->subDays(7))) {
                                                $labels .=
                                                    '<span class="product-label label-circle label-new">New</span>';
                                            }

                                            // Image handling
                                            $imagePath = !empty($product->attachments[0])
                                                ? asset('storage/' . $product->attachments[0])
                                                : asset('assets/images/placeholder.jpg');

                                        @endphp

                                        <div class="product product-2">
                                            <figure class="product-media">
                                                {!! $labels !!}
                                                <img src="{{ $imagePath }}" alt="{{ $product->product_name }}"
                                                    class="product-image" style="height: 300px; object-fit: cover;">

                                                <div class="product-action-vertical">
                                                    <a href="#" class="btn-product-icon btn-wishlist"
                                                        title="Add to wishlist"></a>
                                                </div>
                                                @guest
                                                    <div
                                                        class="product-action d-flex justify-content-around align-items-center">
                                                        <a href="#signin-modal"
                                                            class="btn-product-icon btn-cart trigger-login"
                                                            data-toggle="modal" title="Add to cart">
                                                            <i class="icon-shopping-bag"></i>
                                                        </a>
                                                        <a href="{{ url('popup/quickView?id=' . $product->id) }}"
                                                            class="btn-product-icon btn-quickview mb-1"
                                                            title="Quick view">
                                                            <i class="icon-eye"></i>
                                                        </a>
                                                    </div>
                                                @endguest

                                                @auth
                                                    <div
                                                        class="product-action d-flex justify-content-around align-items-center">
                                                        <form action="{{ route('cart.add') }}" method="POST">
                                                            @csrf
                                                            <input type="text" hidden name="product_id"
                                                                value="{{ $product->id }}">
                                                            <input type="text" hidden name="quantity" value="1">
                                                            <button type="submit" class="btn-product-icon btn-cart"
                                                                title="Add to cart">
                                                                {{-- <i class="icon-shopping-bag"></i> --}}
                                                            </button>
                                                        </form>
                                                        <a href="{{ url('popup/quickView?id=' . $product->id) }}"
                                                            class="btn-product-icon btn-quickview mb-1"
                                                            title="Quick view">
                                                            {{-- <i class="icon-eye"></i> --}}
                                                        </a>
                                                    </div>
                                                @endauth

                                            </figure>

                                            <div class="product-body">
                                                <div class="product-cat">
                                                    <a href="{{ url('category?id=' . $product->category_id) }}">
                                                        {{ $product->category->category_name ?? 'Uncategorized' }}
                                                    </a>
                                                </div>

                                                <h3 class="product-title">
                                                    <a href="{{ url('product?id=' . $product->id) }}">
                                                        {{ $product->product_name }}
                                                    </a>
                                                </h3>

                                                <div class="product-price">
                                                    {{ format_price($product->price) }}
                                                </div>

                                                <div class="ratings-container">
                                                    <div class="ratings">
                                                        <div class="ratings-val"
                                                            style="width: {{ get_rating_width() }}%;"></div>
                                                    </div>
                                                    <span class="ratings-text">
                                                        ({{ get_review_count() }} Reviews)
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-center py-4 w-100">No products found in this category.</p>
                                    @endforelse

                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>
                <div class="mb-6"></div>

        </main>

        {{-- Laravel Blade component/include for Footer --}}
        @include('user.component.footer')

    </div>

    {{-- The rest of the page structure (Modals, Scripts) is maintained with Blade syntax replaced where needed --}}
    <button id="scroll-top" title="Back to Top"><i class="icon-arrow-up"></i></button>





    {{-- Sign in / Register Modal --}}
    <div class="modal fade" id="signin-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="icon-close"></i></span>
                    </button>

                    <div class="form-box">
                        <div class="form-tab">
                            <ul class="nav nav-pills nav-fill nav-border-anim" role="tablist">
                                <li class="nav-item">

                                    <a class="nav-link active" id="signin-tab" data-toggle="tab" href="#signin"
                                        role="tab" aria-controls="signin" aria-selected="true">Sign In</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="register-tab" data-toggle="tab" href="#register"
                                        role="tab" aria-controls="register" aria-selected="false">Register</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="tab-content-5">
                                <div class="tab-pane fade show active" id="signin" role="tabpanel"
                                    aria-labelledby="signin-tab">
                                    <form method="POST" action="{{ route('login') }}">

                                        @csrf
                                        <div>
                                            <x-input-label for="email" :value="__('Email')" class="text-2xl" />
                                            <x-text-input id="email" class="block mt-1 py-3 text-2xl w-full"
                                                type="email" name="email" :value="old('email')" required autofocus
                                                autocomplete="username" />
                                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                        </div>
                                        <!-- Password -->
                                        <div class="mt-4">
                                            <x-input-label for="password" :value="__('Password')" class="text-2xl" />

                                            <x-text-input id="password" class="block mt-1 py-3 text-2xl w-full"
                                                type="password" name="password" required
                                                autocomplete="current-password" />

                                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                        </div>

                                        <div class="form-footer">
                                            <button type="submit" class="btn btn-outline-primary-2">
                                                <span>LOG IN</span>
                                                <i class="icon-long-arrow-right"></i>
                                            </button>

                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" name="remember_me"
                                                    class="custom-control-input" id="signin-remember">
                                                <label class="custom-control-label" for="signin-remember">Remember
                                                    Me</label>
                                            </div>

                                            <a href="#" class="forgot-link">Forgot Your Password?</a>
                                        </div>
                                    </form>

                                    <div class="form-choice">
                                        <p class="text-center">or sign in with</p>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <a href="#" class="btn btn-login btn-g">
                                                    <i class="icon-google"></i>
                                                    Login With Google
                                                </a>
                                            </div>
                                            <div class="col-sm-6">
                                                <a href="#" class="btn btn-login btn-f">
                                                    <i class="icon-facebook-f"></i>
                                                    Login With Facebook
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="register" role="tabpanel"
                                    aria-labelledby="register-tab">

                                    <form method="POST" action="{{ route('register') }}">
                                        @csrf
                                        <h1 class="font-sans font-bold text-center text-3xl">Register</h1>
                                        <!-- Name -->
                                        <div>
                                            <x-input-label for="name" :value="__('Name')" class="text-2xl" />
                                            <x-text-input id="name" class="block mt-1 py-3 text-2xl w-full"
                                                type="text" name="name" :value="old('name')" required autofocus
                                                autocomplete="name" />
                                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                        </div>

                                        <!-- Email Address -->
                                        <div class="mt-4">
                                            <x-input-label for="email" :value="__('Email')" class="text-2xl" />
                                            <x-text-input id="email" class="block mt-1 py-3 text-2xl w-full"
                                                type="email" name="email" :value="old('email')" required
                                                autocomplete="username" />
                                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                        </div>

                                        <!-- Password -->
                                        <div class="mt-4">
                                            <x-input-label for="password" :value="__('Password')" class="text-2xl" />

                                            <x-text-input id="password" class="block mt-1 py-3 text-2xl w-full"
                                                type="password" name="password" required
                                                autocomplete="new-password" />

                                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                        </div>

                                        <!-- Confirm Password -->
                                        <div class="mt-4">
                                            <x-input-label for="password_confirmation" :value="__('Confirm Password')"
                                                class="text-2xl" />

                                            <x-text-input id="password_confirmation"
                                                class="block mt-1 py-3 text-2xl w-full" type="password"
                                                name="password_confirmation" required autocomplete="new-password" />

                                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                                        </div>

                                        <div class="flex items-center justify-end mt-4">
                                            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                                href="{{ route('login') }}">
                                                {{ __('Already registered?') }}
                                            </a>

                                            <x-primary-button class="ms-4">
                                                {{ __('Register') }}
                                            </x-primary-button>
                                        </div>
                                    </form>
                                    <div class="form-choice">
                                        <p class="text-center">or sign in with</p>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <a href="#" class="btn btn-login btn-g">
                                                    <i class="icon-google"></i>
                                                    Login With Google
                                                </a>
                                            </div>
                                            <div class="col-sm-6">
                                                <a href="#" class="btn btn-login btn-f">
                                                    <i class="icon-facebook-f"></i>
                                                    Login With Facebook
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Original Scripts are preserved as they are likely needed for the front-end components (Owl Carousel, Modals) --}}
    <script src={{ asset('assets/js/jquery.min.js') }}></script>
    <script src={{ asset('assets/js/bootstrap.bundle.min.js') }}></script>
    <script src={{ asset('assets/js/jquery.hoverIntent.min.js') }}></script>
    <script src={{ asset('assets/js/jquery.waypoints.min.js') }}></script>
    <script src={{ asset('assets/js/superfish.min.js') }}></script>
    <script src={{ asset('assets/js/owl.carousel.min.js') }}></script>
    <script src={{ asset('assets/js/bootstrap-input-spinner.js') }}></script>
    <script src={{ asset('assets/js/jquery.plugin.min.js') }}></script>
    <script src={{ asset('assets/js/jquery.magnific-popup.min.js') }}></script>
    <script src={{ asset('assets/js/jquery.countdown.min.js') }}></script>
    <script src={{ asset('assets/js/main.js') }}></script>
    <script src={{ asset('assets/js/demos/demo-4.js') }}></script>

    {{-- Static JavaScript is kept, AJAX call is removed from the second script as it depends on server-side logic --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.trigger-login').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    $('#signin-modal').modal('show');
                });
            });
        });
    </script>
    <script>
        // NOTE: The original AJAX cart logic is removed because it relies on a PHP backend (`functions/add-to-cart.php`).
        // For a static Blade page, we only keep the visual part of the click (if needed).
        $(document).on('click', '.add-to-cart', function(e) {
            e.preventDefault();

            var addButton = $(this);
            var originalIconClass = 'icon-shopping-bag';
            var successIconClass = 'icon-check';

            // Simulate successful add to cart instantly on a static page
            var currentCount = parseInt($('.cart-count').text()) || 0;
            $('.cart-count').text(currentCount + 1);

            // Set Success State (Icon)
            addButton.find('i').removeClass('icon-refresh animated-icon ' + originalIconClass).addClass(
                successIconClass);

            // Revert button state after 3 seconds
            setTimeout(function() {
                addButton.prop('disabled', false)
                    .find('i').removeClass(successIconClass).addClass(originalIconClass);
            }, 3000);

            // Commented out the redirect: window.location.href = 'index.php';
        });
    </script>

</body>

</html>

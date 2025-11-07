@php

    // $error = $error ?? '';
    // $session_error = Session::get('order_error'); // Assuming you use Laravel Sessions

    $shipping_cost = $shipping_cost ?? 100.0;
    $final_total = $subtotal + $shipping_cost;

    // Helper function equivalent for formatting price in Blade
    $format_price_rs = function ($price) {
        return number_format((float) $price, 2, '.', ',');
    };
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Ecommerce | Checkout</title>
    <meta name="keywords" content="HTML5 Template" />
    <meta name="description" content="Molla - Bootstrap eCommerce Template" />
    <meta name="author" content="p-themes" />

    <meta name="apple-mobile-web-app-title" content="Molla" />
    <meta name="application-name" content="Molla" />
    <meta name="msapplication-TileColor" content="#cc9966" />
    <meta name="theme-color" content="#ffffff" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />
</head>

<body>




    <div class="page-wrapper">
        {{-- Include Header --}}
        @include('user.component.header')
        @include('user.component.toast')

        <main class="main">
            <div class="page-content">
                <div class="checkout">
                    <div class="container">
                        <div class="checkout-discount">
                            {{-- Coupon/Discount section, possibly handled by a Livewire component or a separate form/controller action --}}
                        </div>

                        {{-- Form action points to a Laravel route responsible for placing the order --}}
                        <form id="checkout-form" action="{{ route('checkout.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-lg-9">
                                    <h2 class="checkout-title">Billing Details</h2>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label>First Name *</label>
                                            <input type="text" class="form-control" name="firstname"
                                                value="{{ old('firstname', $user_data['firstname'] ?? '') }}" />
                                            <x-input-error :messages="$errors->get('firstname')" class="mt-2" />
                                        </div>
                                        <div class="col-sm-6">
                                            <label>Last Name *</label>
                                            <input type="text" class="form-control" name="lastname"
                                                value="{{ old('lastname', $user_data['lastname'] ?? '') }}" />
                                            <x-input-error :messages="$errors->get('lastname')" class="mt-2" />
                                        </div>
                                    </div>

                                    <label>Company Name (Optional)</label>
                                    <input type="text" class="form-control" name="company"
                                        value="{{ old('company') }}" />

                                    <label>Country *</label>
                                    <input type="text" class="form-control" name="country" value="Pakistan" />
                                    <x-input-error :messages="$errors->get('country')" class="mt-2" />

                                    <label>Street address *</label>
                                    <input type="text" class="form-control" name="address1"
                                        placeholder="House number and Street name"
                                        value="{{ old('address1', $user_data['address'] ?? '') }}" />
                                    <x-input-error :messages="$errors->get('address')" class="mt-2" />
                                    <input type="text" class="form-control" name="address2"
                                        placeholder="Appartments, suite, unit etc ..." value="{{ old('address2') }}" />
                                    <x-input-error :messages="$errors->get('address2')" class="mt-2" />
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label>Town / City *</label>
                                            <input type="text" class="form-control" name="city"
                                                value="{{ old('city', $user_data['city'] ?? '') }}" />
                                            <x-input-error :messages="$errors->get('city')" class="mt-2" />
                                        </div>
                                        <div class="col-sm-6">
                                            <label>State / County *</label>

                                            <input type="text" class="form-control" name="state"
                                                value="{{ old('state') }}" />
                                            <x-input-error :messages="$errors->get('state')" class="mt-2" />
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label>Postcode / ZIP *</label>
                                            <input type="text" class="form-control" name="postcode"
                                                value="{{ old('postcode', $user_data['postcode'] ?? '') }}" />
                                            <x-input-error :messages="$errors->get('postcode')" class="mt-2" />
                                        </div>
                                        <div class="col-sm-6">
                                            <label>Phone *</label>
                                            <input type="tel" class="form-control" name="phone_number"
                                                value="{{ old('phone_number', $user_data['phone_number'] ?? '') }}" />
                                            <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
                                        </div>
                                    </div>

                                    <label>Email address *</label>
                                    <input type="email" class="form-control" name="email"
                                        value="{{ Auth::user()->email }}" readonly />
                                    <x-input-error :messages="$errors->get('email')" class="mt-2" />

                                    @php

                                        $full_address_string = implode(
                                            ', ',
                                            array_filter([
                                                $user_data['address'] ?? '',
                                                $user_data['city'] ?? '',
                                                $user_data['postcode'] ?? '',
                                                'Pakistan',
                                            ]),
                                        );
                                    @endphp
                                    {{-- Hidden fields to pass default address strings --}}
                                    <input type="hidden" name="shipping_address_string" id="shipping_address_string"
                                        value="{{ $full_address_string }}">
                                    <input type="hidden" name="billing_address_string" id="billing_address_string"
                                        value="{{ $full_address_string }}">
                                    <input type="text" name="total_amount" hidden
                                        value="{{ $subtotal + $shipping_cost }}">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input"
                                            id="checkout-diff-address" />
                                        <label class="custom-control-label" for="checkout-diff-address">Ship to a
                                            different address?</label>
                                    </div>
                                    <label>Order notes (optional)</label>
                                    <textarea class="form-control" cols="30" rows="4" name="order_notes"
                                        placeholder="Notes about your order, e.g. special notes for delivery">{{ old('order_notes') }}</textarea>
                                </div>

                                <aside class="col-lg-3">
                                    <div class="summary">
                                        <h3 class="summary-title">Your Order</h3>
                                        <table class="table table-summary">
                                            <thead>
                                                <tr>
                                                    <th>Product</th>
                                                    <th>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {{-- Loop through cart items passed from Controller --}}
                                                @foreach ($cartItems as $item)
                                                    <tr>
                                                        <td>
                                                            <p>{{ $item->product['product_name'] }}
                                                                (x{{ $item['quantity'] }})
                                                            </p>
                                                        </td>
                                                        <td>Rs.
                                                            {{ $format_price_rs($item->product['price'] * $item['quantity']) }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                <tr class="summary-subtotal">
                                                    <td>Subtotal:</td>
                                                    <td>Rs. {{ $format_price_rs($subtotal) }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Shipping:</td>
                                                    <td>Rs. {{ $format_price_rs($shipping_cost) }}</td>
                                                </tr>
                                                <tr class="summary-total">
                                                    <td>Total:</td>
                                                    <td>Rs. {{ $format_price_rs($subtotal + $shipping_cost) }}</td>
                                                </tr>
                                            </tbody>
                                        </table>

                                        <div class="accordion-summary" id="accordion-payment">
                                            <div class="card">
                                                <div class="card-header">
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" id="cod_radio" name="payment_method"
                                                            value="cod"
                                                            class="custom-control-input payment-method-radio" checked>
                                                        <label class="custom-control-label" for="cod_radio">Cash on
                                                            Delivery</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="card">
                                                <div class="card-header">
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" id="paypal_radio" name="payment_method"
                                                            value="paypal"
                                                            class="custom-control-input payment-method-radio">
                                                        <label class="custom-control-label" for="paypal_radio">
                                                            PayPal
                                                            <img src="{{ asset('assets/images/payments-summary.png') }}"
                                                                alt="PayPal" class="float-right"
                                                                style="height: 20px;">
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- Hidden inputs for server processing --}}
                                        <input type="hidden" name="total_amount"
                                            value="{{ number_format($final_total, 2, '.', '') }}">
                                        <input type="hidden" name="shipping_cost"
                                            value="{{ number_format($shipping_cost, 2, '.', '') }}">

                                        <button type="submit" id="cod-submit-button"
                                            class="btn btn-outline-primary-2 btn-order btn-block">
                                            <span class="">Place Order</span>
                                            {{-- <span class="btn-hover-text">Proceed to Checkout</span> --}}
                                        </button>

                                        <div id="paypal-button-container" style="margin-top: 15px; display: none;">
                                        </div>

                                    </div>
                                </aside>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>

        {{-- Include Footer --}}
        @include('user.component.footer')
    </div>

    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.hoverIntent.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.waypoints.min.js') }}"></script>
    <script src="{{ asset('assets/js/superfish.min.js') }}"></script>
    <script src="{{ asset('assets/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>

    {{-- PayPal SDK using Blade interpolation for the public client ID --}}
    {{-- <script
        src="https://www.paypal.com/sdk/js?client-id={{ $paypal_client_id_public ?? 'YOUR_PAYPAL_CLIENT_ID' }}&currency=USD">
    </script> --}}

    <script>
        $(document).ready(function() {
            // Use Blade for final total and format as a JS float
            const FINAL_TOTAL = {{ number_format($final_total, 2, '.', '') }};
            const COD_BUTTON = $('#cod-submit-button');
            const PAYPAL_CONTAINER = $('#paypal-button-container');

            // 1. Payment Method Toggle Logic
            $('.payment-method-radio').on('change', function() {
                if ($(this).val() === 'paypal') {
                    COD_BUTTON.hide();
                    PAYPAL_CONTAINER.show();
                } else {
                    COD_BUTTON.show();
                    PAYPAL_CONTAINER.hide();
                }
            });
            // Initialize button visibility on load
            $('.payment-method-radio:checked').trigger('change');

            // 2. PayPal Button Setup
            paypal.Buttons({
                createOrder: function(data, actions) {
                    // Client-side validation before creating PayPal order
                    if (!document.getElementById('checkout-form').checkValidity()) {
                        alert('Please fill out all  billing and shipping fields.');
                        document.getElementById('checkout-form').reportValidity();
                        return false;
                    }

                    return actions.order.create({
                        purchase_units: [{
                            amount: {
                                value: FINAL_TOTAL
                            },
                            description: 'Kharido.pk Order'
                        }]
                    });
                },
                onApprove: function(data, actions) {
                    return actions.order.capture().then(function(details) {
                        // Start processing on the server side
                        processServerPayment(data.orderID, details);
                    });
                },
                onCancel: function(data) {
                    console.log('PayPal payment was cancelled.', data);
                    alert('PayPal payment was cancelled.');
                },
                onError: function(err) {
                    console.error("PayPal Error:", err);
                    alert('An error occurred during the PayPal transaction. Please check the console.');
                }
            }).render('#paypal-button-container');

            // 3. Server-Side Finalization Function for PayPal
            function processServerPayment(paypalOrderID, paypalDetails) {
                // Collect all form data (including manually typed addresses)
                var formData = $('#checkout-form').serializeArray();

                // Build the most up-to-date address string from the form fields
                var currentAddress = $('input[name="address1"]').val() + ', ' +
                    $('input[name="address2"]').val() + ', ' +
                    $('input[name="city"]').val() + ', ' +
                    $('input[name="state"]').val() + ', ' +
                    $('input[name="postcode"]').val() + ', ' +
                    $('input[name="country"]').val();

                // Append/Update custom data fields
                formData.push({
                    name: '_token',
                    value: $('meta[name="csrf-token"]').attr(
                        'content') // Ensure you have the CSRF token if using AJAX
                });
                formData.push({
                    name: 'paypal_order_id',
                    value: paypalOrderID
                });
                formData.push({
                    name: 'payment_method',
                    value: 'paypal'
                });
                // Overwrite the hidden address strings with the CURRENT form input values
                formData.push({
                    name: 'shipping_address_string',
                    value: currentAddress
                });
                formData.push({
                    name: 'billing_address_string',
                    value: currentAddress
                });

                // Temporarily disable buttons/show loading state
                // PAYPAL_CONTAINER.html('Processing Payment...');

                // NOTE: Replace 'functions/paypal_capture.php' with your Laravel route for handling PayPal capture/final order placement
                // $.ajax({
                //     url: '#',
                //     method: 'POST',
                //     data: formData,
                //     dataType: 'json',
                //     success: function(response) {
                //         if (response.success) {
                //             // NOTE: Replace 'order_confirmation.php' with your Laravel route
                //             window.location.href = '' +
                //                 response.order_id;
                //         } else {
                //             // NOTE: Use a Laravel route
                //             window.location.href = '';
                //         }
                //     },
                //     error: function(xhr) {
                //         console.error("AJAX Error:", xhr.responseText);
                //         alert('Failed to complete order due to a server error. Please try COD.');
                //         window.location.href = '';
                //     }
                // });
            }
        });
    </script>
</body>

</html>

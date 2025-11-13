@php
    $shipping_cost = $shipping_cost ?? 100.0;
    $final_total = $subtotal + $shipping_cost;

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
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="apple-mobile-web-app-title" content="Molla" />
    <meta name="application-name" content="Molla" />
    <meta name="msapplication-TileColor" content="#cc9966" />
    <meta name="theme-color" content="#ffffff" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" />

    <style>
        #card-element {
            padding: 12px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            background-color: white;
        }

        #card-element.StripeElement--focus {
            border-color: #80bdff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, .25);
        }

        #card-element.StripeElement--invalid {
            border-color: #dc3545;
        }
    </style>
</head>

<body>
    <div class="page-wrapper">
        @include('user.component.header')
        @include('user.component.toast')

        <main class="main">
            <div class="page-content">
                <div class="checkout">
                    <div class="container">
                        <div class="checkout-discount">
                            {{-- Coupon/Discount section --}}
                        </div>

                        <form id="checkout-form" action="{{ route('checkout.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-lg-9">
                                    <h2 class="checkout-title">Billing Details</h2>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label>First Name *</label>
                                            <input type="text" class="form-control" name="firstname" required
                                                value="{{ old('firstname', $user_data['firstname'] ?? '') }}" />
                                            <x-input-error :messages="$errors->get('firstname')" class="mt-2" />
                                        </div>
                                        <div class="col-sm-6">
                                            <label>Last Name *</label>
                                            <input type="text" class="form-control" name="lastname" required
                                                value="{{ old('lastname', $user_data['lastname'] ?? '') }}" />
                                            <x-input-error :messages="$errors->get('lastname')" class="mt-2" />
                                        </div>
                                    </div>

                                    <label>Company Name (Optional)</label>
                                    <input type="text" class="form-control" name="company"
                                        value="{{ old('company') }}" />

                                    <label>Country *</label>
                                    <input type="text" class="form-control" name="country" value="Pakistan"
                                        required />
                                    <x-input-error :messages="$errors->get('country')" class="mt-2" />

                                    <label>Street address *</label>
                                    <input type="text" class="form-control" name="address1" required
                                        placeholder="House number and Street name"
                                        value="{{ old('address1', $user_data['address'] ?? '') }}" />
                                    <x-input-error :messages="$errors->get('address1')" class="mt-2" />
                                    <input type="text" class="form-control" name="address2"
                                        placeholder="Appartments, suite, unit etc ..." value="{{ old('address2') }}" />
                                    <x-input-error :messages="$errors->get('address2')" class="mt-2" />

                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label>Town / City *</label>
                                            <input type="text" class="form-control" name="city" required
                                                value="{{ old('city', $user_data['city'] ?? '') }}" />
                                            <x-input-error :messages="$errors->get('city')" class="mt-2" />
                                        </div>
                                        <div class="col-sm-6">
                                            <label>State / County *</label>
                                            <input type="text" class="form-control" name="state" required
                                                value="{{ old('state') }}" />
                                            <x-input-error :messages="$errors->get('state')" class="mt-2" />
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label>Postcode / ZIP *</label>
                                            <input type="text" class="form-control" name="postcode" required
                                                value="{{ old('postcode', $user_data['postcode'] ?? '') }}" />
                                            <x-input-error :messages="$errors->get('postcode')" class="mt-2" />
                                        </div>
                                        <div class="col-sm-6">
                                            <label>Phone *</label>
                                            <input type="tel" class="form-control" name="phone_number" required
                                                value="{{ old('phone_number', $user_data['phone_number'] ?? '') }}" />
                                            <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
                                        </div>
                                    </div>

                                    <label>Email address *</label>
                                    <input type="email" class="form-control" name="email"
                                        value="{{ Auth::user()->email }}" readonly />
                                    <x-input-error :messages="$errors->get('email')" class="mt-2" />


                                    <input type="hidden" name="shipping_address" id="shipping_address_string"
                                        value="">
                                    <input type="hidden" name="billing_address" id="billing_address_string"
                                        value="">
                                    <input type="hidden" name="total_amount"
                                        value="{{ number_format($final_total, 2, '.', '') }}">
                                    <input type="hidden" name="shipping_cost"
                                        value="{{ number_format($shipping_cost, 2, '.', '') }}">
                                    <input type="hidden" name="paypal_order_id" id="paypal_order_id"
                                        value="">

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
                                                    <td>Rs. {{ $format_price_rs($final_total) }}</td>
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

                                            <div class="card">
                                                <div class="card-header">
                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" id="stripe_radio" name="payment_method"
                                                            value="stripe"
                                                            class="custom-control-input payment-method-radio">
                                                        <label class="custom-control-label" for="stripe_radio">
                                                            Credit / Debit Card (Stripe)
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- COD Submit Button -->
                                        <button type="submit" id="cod-submit-button"
                                            class="btn btn-outline-primary-2 btn-order btn-block">
                                            <span class="">Place Order</span>
                                        </button>

                                        <!-- PayPal Button Container -->
                                        <div id="paypal-button-container" style="margin-top: 15px; display: none;">
                                        </div>

                                        <!-- Stripe Card Container -->
                                        <div id="stripe-card-container" style="display:none; margin-top: 15px;">
                                            <div id="card-element" class="form-control"></div>
                                            <div id="card-errors" role="alert"
                                                style="color: #dc3545; margin-top: 10px; font-size: 14px;"></div>
                                            <button type="button" id="stripe-pay-button"
                                                class="btn btn-outline-primary-2 btn-order btn-block mt-3">
                                                Pay Rs. {{ number_format($final_total, 2) }}
                                            </button>
                                        </div>

                                    </div>
                                </aside>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>

        @include('user.component.footer')
    </div>

    <!-- Scripts -->
    <script src="https://js.stripe.com/v3/"></script>

    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.hoverIntent.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.waypoints.min.js') }}"></script>
    <script src="{{ asset('assets/js/superfish.min.js') }}"></script>
    <script src="{{ asset('assets/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>

    <!-- Stripe.js -->

    <!-- PayPal SDK -->
    <script
        src="https://www.paypal.com/sdk/js?client-id=AUOVyqA4VVr09Y0aGt6HFHb0VmLV-5sEcDqKOYI3VXN-U_B2zZ0AB2ZnGOJHfr3jTP_b5hNO1OAfxaJs&currency=USD">
    </script>

    <script>
        $(document).ready(function() {
            const FINAL_TOTAL = {{ number_format($final_total, 2, '.', '') }};
            const COD_BUTTON = $('#cod-submit-button');
            const PAYPAL_CONTAINER = $('#paypal-button-container');
            const STRIPE_CONTAINER = $('#stripe-card-container');
            const STRIPE_PAY_BUTTON = $('#stripe-pay-button');
            const CHECKOUT_FORM = $('#checkout-form');

            console.log('Checkout page loaded');

            // ==================== Initialize Stripe ====================
            const stripe = Stripe('{{ config('services.stripe.public') }}');
            const elements = stripe.elements();
            const cardElement = elements.create('card', {
                style: {
                    base: {
                        fontSize: '16px',
                        color: '#32325d',
                        fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                        '::placeholder': {
                            color: '#aab7c4'
                        }
                    },
                    invalid: {
                        color: '#fa755a',
                        iconColor: '#fa755a'
                    }
                }
            });

            let cardMounted = false;

            // ==================== Toast Helper ====================
            function showToast(message, type = 'info') {
                const existingToast = document.getElementById('toast');
                if (existingToast) existingToast.remove();

                const styles = {
                    success: {
                        bg: '#28a745',
                        icon: '✓'
                    },
                    error: {
                        bg: '#dc3545',
                        icon: '✕'
                    },
                    info: {
                        bg: '#17a2b8',
                        icon: 'ℹ'
                    }
                };
                const style = styles[type] || styles.info;

                document.body.insertAdjacentHTML('beforeend',
                    `<div id="toast" style="position: fixed; top: 20px; right: 20px; z-index: 9999; background: ${style.bg}; color: white; padding: 15px 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); display: flex; align-items: center; gap: 10px; min-width: 250px;">
                        <span style="font-size: 20px; font-weight: bold;">${style.icon}</span>
                        <span style="font-size: 16px;">${message}</span>
                    </div>`
                );

                const toast = document.getElementById('toast');
                setTimeout(() => {
                    toast.style.opacity = '0';
                    toast.style.transition = 'opacity 0.5s';
                    setTimeout(() => toast.remove(), 500);
                }, 3000);
            }

            // ==================== Address String Builder ====================
            function updateAddressStrings() {
                const address = [
                    $('input[name="address1"]').val(),
                    $('input[name="address2"]').val(),
                    $('input[name="city"]').val(),
                    $('input[name="state"]').val(),
                    $('input[name="postcode"]').val(),
                    $('input[name="country"]').val()
                ].filter(Boolean).join(', ');

                $('#shipping_address_string').val(address);
                $('#billing_address_string').val(address);
            }

            $('input[name="address1"], input[name="address2"], input[name="city"], input[name="state"], input[name="postcode"], input[name="country"]')
                .on('change keyup', updateAddressStrings);
            updateAddressStrings();

            // ==================== COD Form Submission ====================
            CHECKOUT_FORM.on('submit', function(e) {
                const selectedMethod = $('input[name="payment_method"]:checked').val();
                console.log('Form submitted with method:', selectedMethod);

                if (selectedMethod === 'cod') {
                    updateAddressStrings();
                    return true; // Allow form submission
                }

                // Prevent form submission for PayPal and Stripe
                e.preventDefault();
                return false;
            });

            // ==================== Payment Method Toggle ====================
            $('.payment-method-radio').on('change', function() {
                const method = $(this).val();
                console.log('Payment method changed to:', method);

                // Hide all payment options first
                STRIPE_CONTAINER.hide();
                COD_BUTTON.hide();
                PAYPAL_CONTAINER.hide();

                // Show selected payment method
                if (method === 'stripe') {
                    if (!stripe) {
                        showToast('Stripe is not configured. Please contact support.', 'error');
                        $('#cod_radio').prop('checked', true).trigger('change');
                        return;
                    }
                    console.log('Showing Stripe container');
                    STRIPE_CONTAINER.show();

                    // Mount Stripe card element only once
                    if (!cardMounted) {
                        console.log('Mounting Stripe card element');
                        cardElement.mount('#card-element');
                        cardMounted = true;

                        // Handle real-time validation errors
                        cardElement.on('change', function(event) {
                            const displayError = document.getElementById('card-errors');
                            if (event.error) {
                                displayError.textContent = event.error.message;
                            } else {
                                displayError.textContent = '';
                            }
                        });
                    }
                } else if (method === 'cod') {
                    console.log('Showing COD button');
                    COD_BUTTON.show();
                } else if (method === 'paypal') {
                    console.log('Showing PayPal container');
                    PAYPAL_CONTAINER.show();
                }
            });

            // ==================== Stripe Payment Handler ====================
            STRIPE_PAY_BUTTON.on('click', async function(e) {
                e.preventDefault();
                console.log('Stripe pay button clicked');

                // Validate form
                if (!CHECKOUT_FORM[0].checkValidity()) {
                    showToast('Please fill out all required fields.', 'error');
                    CHECKOUT_FORM[0].reportValidity();
                    return;
                }

                updateAddressStrings();

                // Disable button and show loading
                STRIPE_PAY_BUTTON.prop('disabled', true).text('Processing...');
                showToast('Creating payment...', 'info');

                try {
                    // Step 1: Create Payment Intent
                    console.log('Creating payment intent...');
                    const formData = CHECKOUT_FORM.serializeArray();
                    formData.push({
                        name: 'payment_method',
                        value: 'stripe'
                    });

                    const intentResponse = await $.ajax({
                        url: '{{ route('checkout.stripe.intent') }}',
                        method: 'POST',
                        data: $.param(formData),
                        dataType: 'json',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    if (!intentResponse.clientSecret) {
                        throw new Error('Failed to create payment intent');
                    }

                    console.log('Payment Intent created successfully');

                    // Step 2: Confirm Card Payment
                    console.log('Confirming card payment...');
                    const {
                        error,
                        paymentIntent
                    } = await stripe.confirmCardPayment(
                        intentResponse.clientSecret, {
                            payment_method: {
                                card: cardElement,
                                billing_details: {
                                    name: $('input[name="firstname"]').val() + ' ' + $(
                                        'input[name="lastname"]').val(),
                                    email: $('input[name="email"]').val(),
                                    phone: $('input[name="phone_number"]').val(),
                                    address: {
                                        line1: $('input[name="address1"]').val(),
                                        line2: $('input[name="address2"]').val(),
                                        city: $('input[name="city"]').val(),
                                        state: $('input[name="state"]').val(),
                                        postal_code: $('input[name="postcode"]').val(),
                                        country: 'US'
                                    }
                                }
                            }
                        }
                    );

                    if (error) {
                        throw new Error(error.message);
                    }

                    console.log('Payment confirmed:', paymentIntent.status);

                    // Step 3: Create Order on Server
                    if (paymentIntent.status === 'succeeded') {
                        showToast('Payment successful! Creating order...', 'success');

                        const confirmData = formData.concat([{
                            name: 'stripe_payment_intent_id',
                            value: paymentIntent.id
                        }]);

                        const orderResponse = await $.ajax({
                            url: '{{ route('checkout.stripe.store') }}',
                            method: 'POST',
                            data: $.param(confirmData),
                            dataType: 'json',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });

                        if (orderResponse.success && orderResponse.redirect_url) {
                            showToast('Order placed successfully! Redirecting...', 'success');
                            setTimeout(() => {
                                window.location.href = orderResponse.redirect_url;
                            }, 1000);
                        } else {
                            throw new Error('Order creation failed');
                        }
                    }

                } catch (error) {
                    console.error('Stripe payment error:', error);

                    let errorMessage = 'Payment failed. Please try again.';

                    if (error.responseJSON && error.responseJSON.error) {
                        errorMessage = error.responseJSON.error;
                    } else if (error.message) {
                        errorMessage = error.message;
                    }

                    showToast(errorMessage, 'error');
                    STRIPE_PAY_BUTTON.prop('disabled', false).text(
                        'Pay Rs. {{ number_format($final_total, 2) }}');
                }
            });

            // ==================== PayPal Integration ====================
            console.log('Initializing PayPal buttons...');
            paypal.Buttons({
                createOrder: function(data, actions) {
                    console.log('PayPal createOrder called');
                    if (!CHECKOUT_FORM[0].checkValidity()) {
                        showToast('Please fill out all required fields.', 'error');
                        CHECKOUT_FORM[0].reportValidity();
                        return Promise.reject(new Error('Form validation failed'));
                    }

                    updateAddressStrings();

                    return actions.order.create({
                        purchase_units: [{
                            amount: {
                                value: FINAL_TOTAL.toFixed(2),
                                currency_code: 'USD'
                            },
                            description: 'Molla Ecommerce Order'
                        }]
                    });
                },

                onApprove: function(data, actions) {
                    console.log('✅ PayPal payment approved. Order ID:', data.orderID);

                    return actions.order.capture().then(function(details) {
                        console.log('✅ Payment captured:', details.status);
                        showToast('Payment successful! Creating your order...', 'success');
                        processServerPayment(data.orderID, details);
                    });
                },

                onCancel: function(data) {
                    console.log('❌ PayPal payment cancelled by user');
                    showToast('Payment was cancelled.', 'info');
                },

                onError: function(err) {
                    console.error('❌ PayPal SDK Error:', err);
                    showToast('PayPal error occurred. Please try again.', 'error');
                }
            }).render('#paypal-button-container');

            function processServerPayment(paypalOrderID, details) {
                console.log('📤 Sending order to server for processing...');

                updateAddressStrings();

                const formData = CHECKOUT_FORM.serializeArray();
                formData.push({
                    name: 'paypal_order_id',
                    value: paypalOrderID
                }, {
                    name: 'payment_method',
                    value: 'paypal'
                });

                PAYPAL_CONTAINER.find('button').prop('disabled', true);
                showToast('Processing your order...', 'info');

                $.ajax({
                    url: '{{ route('checkout.paypal.store') }}',
                    method: 'POST',
                    data: $.param(formData),
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    timeout: 30000,

                    success: function(response) {
                        console.log('✅ Server response:', response);

                        if (response.success === true && response.redirect_url) {
                            showToast('Order placed successfully! Redirecting...', 'success');

                            setTimeout(function() {
                                window.location.href = response.redirect_url;
                            }, 1000);
                        } else {
                            console.error('❌ Unexpected server response:', response);
                            showToast('Order processing error. Please contact support.', 'error');
                            PAYPAL_CONTAINER.find('button').prop('disabled', false);
                        }
                    },

                    error: function(xhr, status, error) {
                        console.error('❌ Server Error:', {
                            status: xhr.status,
                            statusText: status,
                            error: error,
                            response: xhr.responseText
                        });

                        PAYPAL_CONTAINER.find('button').prop('disabled', false);

                        let message = 'Order processing failed. Please try again.';

                        try {
                            const errorResponse = JSON.parse(xhr.responseText);

                            if (xhr.status === 422 && errorResponse.messages) {
                                const errors = Object.values(errorResponse.messages).flat();
                                message = 'Validation error: ' + errors.join('; ');
                            } else if (errorResponse.error) {
                                message = errorResponse.error;
                            }

                            if (errorResponse.details) {
                                console.error('Server error details:', errorResponse.details);
                            }
                        } catch (e) {
                            console.error('Failed to parse error response:', e);
                        }

                        if (xhr.status === 0) {
                            message = 'Network error. Please check your connection.';
                        } else if (status === 'timeout') {
                            message = 'Request timeout. Please try again.';
                        }

                        showToast(message, 'error');
                    }
                });
            }

            // Initialize payment method display
            $('.payment-method-radio:checked').trigger('change');
            console.log('Initial payment method set');
        });
    </script>
</body>

</html>

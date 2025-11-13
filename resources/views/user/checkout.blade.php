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
                                        </div>

                                        <button type="submit" id="cod-submit-button"
                                            class="btn btn-outline-primary-2 btn-order btn-block">
                                            <span class="">Place Order</span>
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

        @include('user.component.footer')
    </div>

    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.hoverIntent.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.waypoints.min.js') }}"></script>
    <script src="{{ asset('assets/js/superfish.min.js') }}"></script>
    <script src="{{ asset('assets/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>

    <script
        src="https://www.paypal.com/sdk/js?client-id=AUOVyqA4VVr09Y0aGt6HFHb0VmLV-5sEcDqKOYI3VXN-U_B2zZ0AB2ZnGOJHfr3jTP_b5hNO1OAfxaJs&currency=USD">
    </script>

    {{-- AXbmCprgHhbefFilx3Oy-8KocEGMBhNqOj01iirOVY1hdbpNG9ZcGmmi_Cw7AmeKHl7yA6veLp26SCSF --}}
    <script>
        $(document).ready(function() {
            const FINAL_TOTAL = {{ number_format($final_total, 2, '.', '') }};
            const COD_BUTTON = $('#cod-submit-button');
            const PAYPAL_CONTAINER = $('#paypal-button-container');
            const CHECKOUT_FORM = $('#checkout-form');

            // Toast Helper - Pure UI feedback (minimal JS)
            function showToast(message, type = 'info') {
                const existingToast = document.getElementById('toast');
                if (existingToast) existingToast.remove();

                const styles = {
                    success: {
                        bg: 'bg-green-500/90',
                        border: 'border-green-400',
                        icon: '<svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>'
                    },
                    error: {
                        bg: 'bg-rose-500/90',
                        border: 'border-rose-400',
                        icon: '<svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>'
                    },
                    info: {
                        bg: 'bg-blue-500/90',
                        border: 'border-blue-400',
                        icon: '<svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 20h.01"/></svg>'
                    }
                };
                const style = styles[type] || styles.info;

                document.body.insertAdjacentHTML('beforeend',
                    `<div id="toast" class="fixed top-5 right-5 z-[9999] flex items-center gap-3 px-5 py-3 rounded-xl shadow-lg border text-white backdrop-blur-lg animate-fadeIn ${style.bg} ${style.border}">
                    ${style.icon}
                    <span class="text-xl font-medium tracking-wide">${message}</span>
                </div>`
                );

                const toast = document.getElementById('toast');
                setTimeout(() => {
                    toast.style.opacity = '0';
                    toast.style.transform = 'translateX(5px)';
                    setTimeout(() => toast.remove(), 500);
                }, 3000);
            }

            // Address string builder - Only for UI convenience, validation happens server-side
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

            // Initialize - All fields enabled for server validation
            $('.individual-address-field').prop('disabled', false);
            $('.address-field').on('change keyup', updateAddressStrings);
            updateAddressStrings();

            // COD Form Submission - Direct server-side processing
            CHECKOUT_FORM.on('submit', function(e) {
                if ($('input[name="payment_method"]:checked').val() === 'cod') {
                    updateAddressStrings();
                    return true; // Server handles everything
                }
                e.preventDefault(); // Block form submit for PayPal
                return false;
            });

            // Payment method toggle - Pure UI
            $('.payment-method-radio').on('change', function() {
                const method = $(this).val();
                if (method === 'paypal') {
                    COD_BUTTON.hide();
                    PAYPAL_CONTAINER.show();
                    CHECKOUT_FORM.prop('action', '#');
                } else {
                    COD_BUTTON.show();
                    PAYPAL_CONTAINER.hide();
                    CHECKOUT_FORM.prop('action', '{{ route('checkout.store') }}');
                }
            });
            $('.payment-method-radio:checked').trigger('change');

            // PayPal Integration - Minimal client logic, maximum server validation
            paypal.Buttons({
             
                // Step 1: Create PayPal order (client-side only for PayPal SDK)
                createOrder: function(data, actions) {
                    // Basic HTML5 validation before opening PayPal popup
                    if (!CHECKOUT_FORM[0].checkValidity()) {
                        showToast('Please fill out all required fields.', 'error');
                        CHECKOUT_FORM[0].reportValidity();
                        return Promise.reject(new Error('Form validation failed'));
                    }

                    updateAddressStrings();

                    // Create PayPal order (required by PayPal SDK)
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

                // Step 2: Payment approved - Capture and send to server
                onApprove: function(data, actions) {
                    console.log('✅ PayPal payment approved. Order ID:', data.orderID);

                    // Capture payment (required by PayPal SDK)
                    return actions.order.capture().then(function(details) {
                        console.log('✅ Payment captured:', details.status);
                        showToast('Payment successful! Creating your order...', 'success');

                        // Send everything to server for validation and order creation
                        processServerPayment(data.orderID, details);
                    });
                },

                // Step 3: Payment cancelled
                onCancel: function(data) {
                    console.log('❌ PayPal payment cancelled by user');
                    showToast('Payment was cancelled.', 'info');
                },

                // Step 4: PayPal SDK error
                onError: function(err) {
                    console.error('❌ PayPal SDK Error:', err);
                    showToast('PayPal error occurred. Please try again.', 'error');
                }
            }).render('#paypal-button-container');

            /**
             * SERVER-SIDE PROCESSING
             * This function only sends data to server - all validation,
             * order creation, and business logic happens on the server
             */
            function processServerPayment(paypalOrderID, details) {
                console.log('📤 Sending order to server for processing...');

                updateAddressStrings(); // Final sync

                // Collect all form data
                const formData = CHECKOUT_FORM.serializeArray();
                formData.push({
                    name: 'paypal_order_id',
                    value: paypalOrderID
                }, {
                    name: 'payment_method',
                    value: 'paypal'
                });

                // Disable button to prevent double submission
                PAYPAL_CONTAINER.find('button').prop('disabled', true);
                showToast('Processing your order...', 'info');

                // Send to server - Server does ALL the heavy lifting
                $.ajax({
                    url: '{{ route('checkout.paypal.store') }}',
                    method: 'POST',
                    data: $.param(formData),
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    timeout: 30000, // 30 second timeout

                    // Success: Server validated everything and created order
                    success: function(response) {
                        console.log('✅ Server response:', response);

                        if (response.success === true && response.redirect_url) {
                            showToast('Order placed successfully! Redirecting...', 'success');

                            // Small delay for user to see success message
                            setTimeout(function() {
                                window.location.href = response.redirect_url;
                            }, 1000);
                        } else {
                            // Unexpected response format
                            console.error('❌ Unexpected server response:', response);
                            showToast('Order processing error. Please contact support.', 'error');
                            PAYPAL_CONTAINER.find('button').prop('disabled', false);
                        }
                    },

                    // Error: Server validation failed or system error
                    error: function(xhr, status, error) {
                        console.error('❌ Server Error:', {
                            status: xhr.status,
                            statusText: status,
                            error: error,
                            response: xhr.responseText
                        });

                        PAYPAL_CONTAINER.find('button').prop('disabled', false);

                        let message = 'Order processing failed. Please try again.';

                        // Parse server error message
                        try {
                            const errorResponse = JSON.parse(xhr.responseText);

                            if (xhr.status === 422 && errorResponse.messages) {
                                // Validation errors from server
                                const errors = Object.values(errorResponse.messages).flat();
                                message = 'Validation error: ' + errors.join('; ');
                            } else if (errorResponse.error) {
                                message = errorResponse.error;
                            }

                            // Log detailed error for debugging
                            if (errorResponse.details) {
                                console.error('Server error details:', errorResponse.details);
                            }
                        } catch (e) {
                            console.error('Failed to parse error response:', e);
                        }

                        // Network/timeout errors
                        if (xhr.status === 0) {
                            message = 'Network error. Please check your connection.';
                        } else if (status === 'timeout') {
                            message = 'Request timeout. Please try again.';
                        }

                        showToast(message, 'error');
                    }
                });
            }
        });
    </script>
</body>

</html>

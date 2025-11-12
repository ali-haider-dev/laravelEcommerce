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
                                    <input type="text" class="form-control" name="country" value="Pakistan" required />
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

                                    @php
                                        $full_address_string = implode(', ', array_filter([
                                            $user_data['address'] ?? '',
                                            $user_data['city'] ?? '',
                                            $user_data['postcode'] ?? '',
                                            'Pakistan',
                                        ]));
                                    @endphp
                                    
                                    <input type="hidden" name="shipping_address_string" id="shipping_address_string"
                                        value="{{ $full_address_string }}">
                                    <input type="hidden" name="billing_address_string" id="billing_address_string"
                                        value="{{ $full_address_string }}">
                                    <input type="hidden" name="total_amount" value="{{ number_format($final_total, 2, '.', '') }}">
                                    <input type="hidden" name="shipping_cost" value="{{ number_format($shipping_cost, 2, '.', '') }}">
                                    <input type="hidden" name="paypal_order_id" id="paypal_order_id" value="">
                                    
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

    <script src="https://www.paypal.com/sdk/js?client-id=AXbmCprgHhbefFilx3Oy-8KocEGMBhNqOj01iirOVY1hdbpNG9ZcGmmi_Cw7AmeKHl7yA6veLp26SCSF&currency=USD"></script>

    <script>
        $(document).ready(function() {
            const FINAL_TOTAL = parseFloat('{{ number_format($final_total, 2, '.', '') }}');
            const COD_BUTTON = $('#cod-submit-button');
            const PAYPAL_CONTAINER = $('#paypal-button-container');

            // Payment Method Toggle Logic
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

            // PayPal Button Setup
            paypal.Buttons({
                createOrder: function(data, actions) {
                    // Client-side validation
                    const form = document.getElementById('checkout-form');
                    if (!form.checkValidity()) {
                        alert('Please fill out all required billing and shipping fields.');
                        form.reportValidity();
                        return Promise.reject('Form validation failed');
                    }

                    return actions.order.create({
                        purchase_units: [{
                            amount: {
                                value: FINAL_TOTAL.toFixed(2)
                            },
                            description: 'Kharido.pk Order'
                        }]
                    });
                },
                onApprove: function(data, actions) {
                    return actions.order.capture().then(function(details) {
                        // Store PayPal order ID in hidden field
                        $('#paypal_order_id').val(data.orderID);
                        
                        // Build current address string from form
                        const address1 = $('input[name="address1"]').val() || '';
                        const address2 = $('input[name="address2"]').val() || '';
                        const city = $('input[name="city"]').val() || '';
                        const state = $('input[name="state"]').val() || '';
                        const postcode = $('input[name="postcode"]').val() || '';
                        const country = $('input[name="country"]').val() || '';
                        
                        const currentAddress = [address1, address2, city, state, postcode, country]
                            .filter(val => val.trim() !== '')
                            .join(', ');
                        
                        // Update hidden address fields with current form values
                        $('#shipping_address_string').val(currentAddress);
                        $('#billing_address_string').val(currentAddress);
                        
                        // Submit the form normally
                        $('#checkout-form').submit();
                    });
                },
                onCancel: function(data) {
                    console.log('PayPal payment was cancelled.', data);
                    alert('PayPal payment was cancelled.');
                },
                onError: function(err) {
                    console.error("PayPal Error:", err);
                    alert('An error occurred during the PayPal transaction. Please try again.');
                }
            }).render('#paypal-button-container');
        });
    </script>
</body>

</html>
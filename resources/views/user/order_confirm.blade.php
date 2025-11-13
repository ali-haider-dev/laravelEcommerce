

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Order Confirmed | Molla eCommerce</title>
    <!-- Assuming your project uses Tailwind CSS or includes its classes in the compiled assets -->
    
    <!-- Custom Styles for Green Theme and Confetti -->
    <style>
        .sparkle-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            overflow: hidden;
            z-index: 1000;
        }

        @keyframes confetti-fall {
            0% { transform: translateY(-100vh) rotate(0deg); opacity: 1; }
            100% { transform: translateY(100vh) rotate(720deg); opacity: 0.5; }
        }

        .sparkle {
            position: absolute;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background-color: #10B981; /* Tailwind emerald-500 */
            opacity: 0;
            animation: confetti-fall 5s linear infinite;
        }
        
        /* Generate multiple sparkles with varying delay, duration, and position */
        @for ($i = 1; $i <= 50; $i++)
            .sparkle:nth-child({{ $i }}) {
                left: {{ rand(0, 100) }}vw;
                top: {{ rand(-10, -50) }}vh;
                animation-delay: {{ $i * 0.1 }}s;
                animation-duration: {{ rand(3, 7) }}s;
                background-color: 
                    @if ($i % 3 == 0) #34D399; /* Emerald-400 */
                    @elseif ($i % 2 == 0) #059669; /* Emerald-600 */
                    @else #D1FAE5; /* Emerald-100 */
                    @endif
                width: {{ rand(5, 12) }}px;
                height: {{ rand(5, 12) }}px;
            }
        @endfor
    </style>

    <!-- Standard Molla Assets (kept for styling consistency) -->
    <link rel="apple-touch-icon" sizes="180x180" href="assets/images/icons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/images/icons/favicon-32x32.png">
    <link rel="stylesheet" href="assets/vendor/line-awesome/line-awesome/line-awesome/css/line-awesome.min.css">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/plugins/owl-carousel/owl.carousel.css">
    <link rel="stylesheet" href="assets/css/plugins/magnific-popup/magnific-popup.css">
    <link rel="stylesheet" href="assets/css/plugins/jquery.countdown.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/skins/skin-demo-4.css">
    <link rel="stylesheet" href="assets/css/demos/demo-4.css">
</head>

<body>
    <div class="page-wrapper">
        @include('user.component.header')

        <main class="main">
            <div class="page-content bg-gray-50 min-h-[50vh] flex items-center justify-center py-20">
                <div class="container text-center max-w-2xl bg-white shadow-xl rounded-xl p-10 md:p-16">
                    
                    {{-- Confetti Container --}}
                    <div class="sparkle-container">
                        @for ($i = 0; $i < 50; $i++)
                            <div class="sparkle"></div>
                        @endfor
                    </div>
                    
                    <div class="text-center">
                        {{-- Large Checkmark Icon --}}
                        <svg class="mx-auto h-24 w-24 text-emerald-500 mb-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>

                        <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-4">
                            Order Successfully Placed!
                        </h1>
                        
                        {{-- Display the flash message or a default --}}
                        @if (session('success'))
                            <p class="lead text-xl text-emerald-600 mb-8 font-medium">
                                {{ session('success') }}
                            </p>
                        @else
                            {{-- Fallback message --}}
                            <p class="lead text-xl text-emerald-600 mb-8 font-medium">
                                Your Order is successful. Thank you for your purchase!
                            </p>
                        @endif
                    </div>

                    <div class="border-t border-b border-emerald-100 bg-emerald-50 py-6 mb-8 mt-6">
                        <p class="text-lg text-gray-700 font-semibold mb-2">
                            Your **Order Reference Number** is: 
                        </p>
                        {{-- Use the order number passed from the controller --}}
                        <strong class="text-3xl font-mono tracking-wider text-emerald-700 block select-all">
                            #{{ $order_number ?? 'CONF-XXXXXX' }}
                        </strong>
                        <p class="text-sm text-gray-500 mt-3">
                            A detailed confirmation email has been sent to: 
                            <span class="font-medium text-gray-800">{{ Auth::user()->email ?? 'your.email@example.com' }}</span>
                        </p>
                    </div>
                    
                    <div class="space-y-4 sm:space-y-0 sm:space-x-4 justify-center">
                       
                        <a href="{{ route('user') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-full shadow-md text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition duration-150">
                            <i class="la la-shopping-basket mr-2 text-xl"></i>
                            Continue Shopping
                        </a>
                    </div>
                    
                </div>
            </div>
        </main>

        @include('user.component.footer')
    </div>
    <!-- Plugins JS File -->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/jquery.hoverIntent.min.js"></script>
    <script src="assets/js/jquery.waypoints.min.js"></script>
    <script src="assets/js/superfish.min.js"></script>
    <script src="assets/js/owl.carousel.min.js"></script>
    <script src="assets/js/bootstrap-input-spinner.js"></script>
    <script src="assets/js/jquery.plugin.min.js"></script>
    <script src="assets/js/jquery.magnific-popup.min.js"></script>
    <script src="assets/js/jquery.countdown.min.js"></script>
    <!-- Main JS File -->
    <script src="assets/js/main.js"></script>
    <script src="assets/js/demos/demo-4.js"></script>
</body>

</html>
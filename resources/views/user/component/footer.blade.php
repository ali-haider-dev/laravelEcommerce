@vite(['resources/css/app.css', 'resources/js/app.js'])

<footer class="footer bg-white border-t border-gray-200">

    {{-- Footer Middle: Links and Contact Info --}}
    <div class="footer-middle py-10 border-b border-gray-700 bg-gray-900 text-gray-300">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">

                {{-- Column 1: About --}}
                <div class="col-span-1">
                    <div class="widget">
                        <img src="{{ asset('assets/images/demos/demo-4/logo-footer.png') }}"
                            class="footer-logo mb-4" alt="Footer Logo" width="105" height="25">
                        <p class="text-xl mb-4">Praesent dapibus, neque id cursus ucibus, tortor neque egestas augue,
                            eu
                            vulputate magna eros eu erat.</p>

                        <div class="flex items-center space-x-2 text-base">
                            <i class="icon-phone text-blue-500 text-xl"></i>
                            <div>
                                <p class="text-gray-400">Got Question? Call us 24/7</p>
                                <a href="tel:#"
                                    class="text-lg font-bold text-white hover:text-blue-500 transition duration-300">+0123
                                    456 789</a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Column 2: Useful Links --}}
                <div class="col-span-1">
                    <div class="widget">
                        <h4 class="text-2xl font-semibold text-white mb-4">Useful Links</h4>
                        <ul class="space-y-8 text-base">
                            <li><a href="about.html" class="hover:text-blue-500 text-xl transition duration-300">About
                                    Molla</a>
                            </li>
                            <li><a href="#" class="hover:text-blue-500 text-xl transition duration-300">Our
                                    Services</a>
                            </li>
                            <li><a href="#" class="hover:text-blue-500 text-xl transition duration-300">How to
                                    shop on
                                    Molla</a></li>
                            <li><a href="faq.html" class="hover:text-blue-500 text-xl transition duration-300">FAQ</a>
                            </li>
                            <li><a href="contact.html"
                                    class="hover:text-blue-500 text-xl transition duration-300">Contact
                                    us</a></li>
                        </ul>
                    </div>
                </div>

                {{-- Column 3: Customer Service --}}
                <div class="col-span-1">
                    <div class="widget">
                        <h4 class="text-2xl font-semibold text-white mb-4">Customer Service</h4>
                        <ul class="space-y-8 text-base">
                            <li><a href="#" class="hover:text-blue-500 text-xl transition duration-300">Payment
                                    Methods</a></li>
                            <li><a href="#" class="hover:text-blue-500 text-xl transition duration-300">Money-back
                                    guarantee!</a></li>
                            <li><a href="#"
                                    class="hover:text-blue-500 text-xl transition duration-300">Returns</a></li>
                            <li><a href="#"
                                    class="hover:text-blue-500 text-xl transition duration-300">Shipping</a></li>
                            <li><a href="#" class="hover:text-blue-500 text-xl transition duration-300">Terms and
                                    conditions</a></li>
                            <li><a href="#" class="hover:text-blue-500 text-xl transition duration-300">Privacy
                                    Policy</a>
                            </li>
                        </ul>
                    </div>
                </div>

                {{-- Column 4: My Account --}}
                <div class="col-span-1">
                    <div class="widget">
                        <h4 class="text-2xl font-semibold text-white mb-4">My Account</h4>
                        <ul class="space-y-8 text-base">
                            <li><a href="#" class="hover:text-blue-500 text-xl transition duration-300">Sign
                                    In</a></li>
                            {{-- Placeholder for cart link --}}
                            <li><a href="cart.php" class="hover:text-blue-500 text-xl transition duration-300">View
                                    Cart</a>
                            </li>
                            <li><a href="#" class="hover:text-blue-500 text-xl transition duration-300">My
                                    Wishlist</a>
                            </li>
                            <li><a href="#" class="hover:text-blue-500 text-xl transition duration-300">Track My
                                    Order</a>
                            </li>
                            <li><a href="#" class="hover:text-blue-500 text-xl transition duration-300">Help</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="footer-bottom py-4 bg-gray-800 text-gray-400">
        <div
            class="container mx-auto px-4 flex flex-col md:flex-row justify-between items-center space-y-3 md:space-y-0">
            <p class="text-xl">Copyright &copy; 2019 Kahrido.pk Store . All Rights Reserved.</p>
            <figure class="footer-payments">
                <img src="assets/images/payments.png" alt="Payment methods" class="w-full h-full">
            </figure>
        </div>
    </div>
</footer>

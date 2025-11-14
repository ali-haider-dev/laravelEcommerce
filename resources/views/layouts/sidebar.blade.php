<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Title ko dynamic (dynamic) kar diya gaya hai -->
    <title>{{ $title ?? 'Dashboard' }} | SB Admin Tailwind</title>


    <!-- Font Awesome Icons CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        xintegrity="sha512-SnH5WK+bZxgPHs44uWIX+LLMDJmN2/qJtyZl8D1pE3P+J/nQ9y3P+c9jBqH8q8s/X5Gv8Qv3v9Z"
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <script>
        // Tailwind config ko yahan rakha gaya hai
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary-dark': '#1e3a8a',
                        'primary-light': '#3b82f6',
                    },
                }
            }
        }
    </script>
    <style>
        /* Custom gradient for SB Admin 2 feel */
        .sidebar-gradient {
            background-image: linear-gradient(180deg, #1e3a8a 10%, #172554 100%);
        }

        /* Custom class for the rotated icon effect */
        .rotate-n-15 {
            transform: rotate(-15deg);
        }
    </style>
</head>

<body class="bg-gray-100 min-h-screen">

    <!-- Sidebar Wrapper -->
    <!-- Fixed position, full height, width-64 (md:w-56 for small screen adaptation) -->
    <div id="accordionSidebar"
        class="sidebar-gradient h-screen w-64 fixed top-0 left-0 text-white shadow-2xl z-20 
                flex flex-col overflow-y-auto transition-transform duration-300">

        <!-- Sidebar - Brand -->
        <a class="flex items-center justify-center h-20 text-xl font-bold border-b border-gray-700/50" href="#">
            <div class="rotate-n-15 mr-2">
                <!-- Icon - SB Admin look -->
                <i class="fa-solid fa-laugh-wink"></i>
            </div>
            <div class="mx-3 text-white">SB Admin <sup class="text-sm">2</sup></div>
        </a>

        <!-- Divider -->
        <hr class="border-gray-700 my-0">

        <!-- Main Navigation Links -->
        <ul class="flex flex-col py-2 space-y-1">

            <!-- Nav Item - Dashboard (Active State) -->
            <li class="nav-item">
                <a class="nav-link flex items-center p-3 mx-3 rounded-lg text-sm font-medium bg-indigo-700/30 text-indigo-200"
                    href="{{ route('admin.dashboard') }}">
                    <i class="fa-solid fa-fw fa-tachometer-alt w-5"></i>
                    <span class="ml-3">Dashboard</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="border-gray-700/50 mx-4 my-2">

            <!-- Nav Item - Uploads -->
            <li class="nav-item">
                <a class="nav-link flex items-center p-3 mx-3 rounded-lg text-sm font-medium hover:bg-indigo-700/20 transition-colors duration-150"
                    href="#">
                    <i class="fa-solid fa-cloud-arrow-up fa-fw w-5"></i>
                    <span class="ml-3">Uploads</span>
                </a>
            </li>

            <!-- Nav Item - Products -->
            <li class="nav-item">
                <a class="nav-link flex items-center p-3 mx-3 rounded-lg text-sm font-medium hover:bg-indigo-700/20 transition-colors duration-150"
                    href="{{ route('products') }}">
                    <i class="fa-solid fa-boxes-stacked fa-fw w-5"></i>
                    <span class="ml-3">Products</span>
                </a>
            </li>
            <!-- Nav Item - Products -->
            <li class="nav-item">
                <a class="nav-link flex items-center p-3 mx-3 rounded-lg text-sm font-medium hover:bg-indigo-700/20 transition-colors duration-150"
                    href="{{ route('admin.orders') }}">
                    <i class="fa-solid fa-boxes-stacked fa-fw w-5"></i>
                    <span class="ml-3">Orders</span>
                </a>
            </li>
            <!-- Nav Item - Reports (Dropdown/Collapse Menu) -->
            <li class="nav-item relative">
                <a id="reportsDropdownToggle"
                    class="nav-link flex items-center justify-between p-3 mx-3 rounded-lg text-sm font-medium hover:bg-indigo-700/20 transition-colors duration-150 cursor-pointer"
                    onclick="toggleDropdown('reportsMenu')">
                    <div class="flex items-center">
                        <i class="fa-solid fa-chart-line fa-fw w-5"></i>
                        <span class="ml-3">Reports</span>
                    </div>
                    <!-- Dropdown Arrow Icon -->
                    <i id="reportsIcon"
                        class="fa-solid fa-chevron-down text-xs transform transition-transform duration-300"></i>
                </a>

                <!-- Dropdown Menu (Hidden by default) -->
                <div id="reportsMenu"
                    class="dropdown-menu ml-3 mt-1 py-1 bg-gray-800/80 backdrop-blur-sm rounded-lg shadow-xl hidden transition-all duration-300 overflow-hidden">
                    <h6 class="px-5 py-2 text-xs font-semibold text-gray-400 border-b border-gray-700/50">Reporting
                        Revenue</h6>

                    <a class="dropdown-item block px-5 py-2 text-sm text-gray-200 hover:bg-indigo-700/30"
                        href="{{ route('reports.show', ['type' => 'monthly']) }}">Revenue by Month</a>
                    <a class="dropdown-item block px-5 py-2 text-sm text-gray-200 hover:bg-indigo-700/30"
                        href="{{ route('reports.show', ['type' => 'yearly']) }}">Revenue by Year</a>
                    <a class="dropdown-item block px-5 py-2 text-sm text-gray-200 hover:bg-indigo-700/30"
                        href="{{ route('reports.show', ['type' => 'category']) }}">Revenue by Category</a>
                    <a class="dropdown-item block px-5 py-2 text-sm text-gray-200 hover:bg-indigo-700/30"
                        href="{{ route('reports.show', ['type' => 'categoty_yearly']) }}">Revenue by Cat & Year</a>
                </div>
            </li>

            <!-- Divider -->
            <hr class="border-gray-700/50 mx-4 my-2">



        </ul>

        <!-- Sidebar Toggler (Hidden on small screen, to simulate collapse button on md+) -->
        <div class="mt-auto p-4 flex justify-center ms-auto">
            <button id="sidebarToggle"
                class="w-8 h-8 rounded-full bg-indigo-700 hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors duration-150 text-white hidden md:block"
                title="Toggle Sidebar">
                <i class="fa-solid fa-chevron-left text-xs"></i>
            </button>
        </div>


    </div>

    {{-- <!-- Main Content Area (Yahan page ka main content load hoga) -->
    <div class="ml-64 p-8 min-h-screen transition-all duration-300" id="main-content-wrapper">
        <!-- Blade Slot: Jahan Controller se bheja gaya content load hoga -->
        @isset($slot)
            {{ $slot }}
        @endisset

        <!-- Example Content was here, but has been removed and replaced by $slot -->

    </div> --}}


    <script>
        // Dropdown/Collapse Logic (JavaScript)

        function toggleDropdown(menuId) {
            const menu = document.getElementById(menuId);
            const icon = document.getElementById(menuId.replace('Menu', 'Icon'));

            if (menu.classList.contains('hidden')) {
                // Show the menu
                menu.classList.remove('hidden');
                menu.classList.add('block');

                // Rotate the icon
                if (icon) icon.classList.add('rotate-180');
            } else {
                // Hide the menu
                menu.classList.remove('block');
                menu.classList.add('hidden');

                // Reset the icon rotation
                if (icon) icon.classList.remove('rotate-180');
            }
        }

        // Optional Sidebar Toggle Logic 
        const sidebar = document.getElementById('accordionSidebar');
        const mainContent = document.getElementById('main-content-wrapper'); // ID changed for selection
        const toggleButton = document.getElementById('sidebarToggle');

        if (toggleButton) {
            toggleButton.addEventListener('click', () => {
                const isExpanded = sidebar.classList.contains('w-64');

                if (isExpanded) {
                    // Collapse
                    sidebar.classList.remove('w-64');
                    sidebar.classList.add('w-20');
                    mainContent.classList.remove('ml-64');
                    mainContent.classList.add('ml-20');
                    toggleButton.querySelector('i').classList.replace('fa-chevron-left', 'fa-chevron-right');
                    // Hide text content for a clean collapse animation (SB Admin style)
                    sidebar.querySelectorAll('span, sup, h6').forEach(el => el.classList.add('hidden'));

                } else {
                    // Expand
                    sidebar.classList.remove('w-20');
                    sidebar.classList.add('w-64');
                    mainContent.classList.remove('ml-20');
                    mainContent.classList.add('ml-64');
                    toggleButton.querySelector('i').classList.replace('fa-chevron-right', 'fa-chevron-left');
                    // Show text content
                    sidebar.querySelectorAll('span, sup, h6').forEach(el => el.classList.remove('hidden'));
                }
            });
        }
    </script>

</body>

</html>

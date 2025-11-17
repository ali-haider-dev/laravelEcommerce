<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="font-bold text-3xl text-gray-800">Categories Management</h1>
                <p class="text-sm text-gray-500 mt-1">Organize and manage product categories</p>
            </div>
            <div class="hidden md:flex items-center space-x-2 text-sm text-gray-500">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>Last updated: {{ now()->format('M d, Y h:i A') }}</span>
            </div>
        </div>
    </x-slot>

    @include('user.component.toast')

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">



            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                <div
                    class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl shadow-lg p-6 text-white transform hover:scale-105 transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-purple-100 text-sm font-medium mb-1">Total Categories</p>
                            <p class="text-4xl font-bold">{{ $categories->total() }}</p>
                            <p class="text-purple-200 text-xs mt-2">Active listings</p>
                        </div>
                        <div class="bg-white/20 backdrop-blur-sm p-4 rounded-xl">
                            <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                                </path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-lg p-6 text-white transform hover:scale-105 transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-100 text-sm font-medium mb-1">Total Products</p>
                            <p class="text-4xl font-bold">{{ $categories->sum(fn($cat) => $cat->products->count()) }}
                            </p>
                            <p class="text-blue-200 text-xs mt-2">Across all categories</p>
                        </div>
                        <div class="bg-white/20 backdrop-blur-sm p-4 rounded-xl">
                            <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-2xl shadow-lg p-6 text-white transform hover:scale-105 transition-all duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-emerald-100 text-sm font-medium mb-1">Avg per Category</p>
                            <p class="text-4xl font-bold">
                                {{ $categories->count() > 0 ? round($categories->sum(fn($cat) => $cat->products->count()) / $categories->count(), 1) : 0 }}
                            </p>
                            <p class="text-emerald-200 text-xs mt-2">Products average</p>
                        </div>
                        <div class="bg-white/20 backdrop-blur-sm p-4 rounded-xl">
                            <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                </path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-6 p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div class="flex-1 max-w-md">
                        <form method="GET" action="{{ route('categories.show') }}">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                                <input type="text" name="search" value="{{ request('search') }}"
                                    placeholder="Search categories..."
                                    class="block w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors">
                            </div>
                        </form>
                    </div>

                    <div class="flex items-center gap-3">
                        <button type="button"
                            class="inline-flex items-center px-4 py-2.5 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-colors font-medium">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            Export
                        </button>
                        <button onclick="openAddModal()"
                            class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-purple-600 to-purple-700 text-white rounded-xl hover:from-purple-700 hover:to-purple-800 transition-all font-medium shadow-lg shadow-purple-500/30">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4"></path>
                            </svg>
                            Add Category
                        </button>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                            </path>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-800">All Categories</h3>
                    </div>

                    <div class="flex items-center bg-gray-100 rounded-lg p-1">
                        <button onclick="toggleView('grid')" id="grid-view-btn"
                            class="px-3 py-1.5 rounded-md transition-colors view-toggle">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                                </path>
                            </svg>
                        </button>
                        <button onclick="toggleView('table')" id="table-view-btn"
                            class="px-3 py-1.5 rounded-md transition-colors view-toggle bg-white text-purple-600 shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <div id="grid-view" class="hidden p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                        @forelse($categories as $category)
                            <div
                                class="group bg-white border border-gray-200 rounded-xl p-6 hover:shadow-lg hover:border-purple-300 transition-all duration-300 relative overflow-hidden">
                                <div
                                    class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-purple-100 to-transparent rounded-full -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-500">
                                </div>

                                <div class="relative z-10">
                                    <div
                                        class="w-14 h-14 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                                            </path>
                                        </svg>
                                    </div>

                                    <h3
                                        class="text-lg font-bold text-gray-900 mb-2 group-hover:text-purple-600 transition-colors">
                                        {{ $category->category_name }}
                                    </h3>

                                    <div class="flex items-center justify-between text-sm text-gray-600 mb-4">
                                        <div class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4">
                                                </path>
                                            </svg>
                                            <span class="font-semibold">{{ $category->products->count() }}</span>
                                            products
                                        </div>
                                        <span class="text-xs text-gray-500">ID: {{ $category->id }}</span>
                                    </div>

                                    <div class="flex items-center text-xs text-gray-500 mb-4">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        {{ $category->created_at->format('M d, Y') }}
                                    </div>

                                    <div class="flex items-center gap-2 pt-4 border-t border-gray-100">
                                        <button onclick="openEditModal({{ json_encode($category) }})"
                                            {{-- Pass category object to JS function --}}
                                            class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-purple-50 text-purple-700 rounded-lg hover:bg-purple-100 transition-colors text-sm font-medium">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                            Edit
                                        </button>
                                        <form action="{{ route('categories.delete', $category->id) }}" method="POST"
                                            {{-- ADDED: JavaScript confirmation to the form submission --}}
                                            onsubmit="return confirm('Are you sure you want to delete the category: {{ $category->category_name }}? This action cannot be undone.');">

                                            @method('DELETE')
                                            @csrf

                                            <button type="submit"
                                                class="inline-flex items-center justify-center px-3 py-2 bg-red-50 text-red-700 rounded-lg hover:bg-red-100 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                            </button>
                                        </form>

                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full py-16 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="bg-gray-100 rounded-full p-6 mb-4">
                                        <svg class="h-16 w-16 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                                            </path>
                                        </svg>
                                    </div>
                                    <p class="text-lg font-semibold text-gray-900 mb-1">No categories found</p>
                                    <p class="text-sm text-gray-500 mb-4">Get started by creating your first category
                                    </p>
                                    <button onclick="openAddModal()"
                                        class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4v16m8-8H4"></path>
                                        </svg>
                                        Add First Category
                                    </button>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>

                <div id="table-view" class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    <div class="flex items-center gap-2">
                                        <span>Category</span>
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                                        </svg>
                                    </div>
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Products</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Created Date</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($categories as $category)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div
                                                class="h-10 w-10 flex-shrink-0 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg flex items-center justify-center">
                                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                                                    </path>
                                                </svg>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-bold text-gray-900">
                                                    {{ $category->category_name }}</div>
                                                <div class="text-xs text-gray-500">ID: {{ $category->id }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-purple-50 text-purple-700 ring-1 ring-inset ring-purple-600/20">
                                                {{ $category->products->count() }} items
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex flex-col">
                                            <span
                                                class="text-sm font-medium text-gray-900">{{ $category->created_at->format('M d, Y') }}</span>
                                            <span
                                                class="text-xs text-gray-500">{{ $category->created_at->format('h:i A') }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-2">
                                            <button onclick="openEditModal({{ json_encode($category) }})"
                                                {{-- Pass category object to JS function --}}
                                                class="inline-flex items-center px-3 py-1.5 bg-purple-50 text-purple-700 rounded-lg hover:bg-purple-100 transition-colors text-sm font-medium">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                    </path>
                                                </svg>
                                                Edit
                                            </button>
                                            <form action="{{ route('categories.delete', $category->id) }}"
                                                method="POST" {{-- ADDED: JavaScript confirmation to the form submission --}}
                                                onsubmit="return confirm('Are you sure you want to delete the category: {{ $category->category_name }}? This action cannot be undone.');">

                                                @method('DELETE')
                                                @csrf

                                                <button type="submit"
                                                    class="inline-flex items-center justify-center px-3 py-2 bg-red-50 text-red-700 rounded-lg hover:bg-red-100 transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                        </path>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-16 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="bg-gray-100 rounded-full p-6 mb-4">
                                                <svg class="h-16 w-16 text-gray-400" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                                                    </path>
                                                </svg>
                                            </div>
                                            <p class="text-lg font-semibold text-gray-900 mb-1">No categories found</p>
                                            <p class="text-sm text-gray-500">Start by creating your first category</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($categories->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                        {{ $categories->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- 💡 NEW: Edit Category Modal Component --}}
    @include('components.edit-category-modal')
    @include('components.add-category-modal')
    <script>
        // ... (Existing script code) ...

        // View Toggle
        function toggleView(view) {
            const gridView = document.getElementById('grid-view');
            const tableView = document.getElementById('table-view');
            const gridBtn = document.getElementById('grid-view-btn');
            const tableBtn = document.getElementById('table-view-btn');

            if (view === 'grid') {
                gridView.classList.remove('hidden');
                tableView.classList.add('hidden');
                gridBtn.classList.add('bg-white', 'text-purple-600', 'shadow-sm');
                gridBtn.classList.remove('text-gray-600');
                tableBtn.classList.remove('bg-white', 'text-purple-600', 'shadow-sm');
                tableBtn.classList.add('text-gray-600');
                localStorage.setItem('categoryView', 'grid');
            } else {
                tableView.classList.remove('hidden');
                gridView.classList.add('hidden');
                tableBtn.classList.add('bg-white', 'text-purple-600', 'shadow-sm');
                tableBtn.classList.remove('text-gray-600');
                gridBtn.classList.remove('bg-white', 'text-purple-600', 'shadow-sm');
                gridBtn.classList.add('text-gray-600');
                localStorage.setItem('categoryView', 'table');
            }
        }

        // Load saved view preference
        document.addEventListener('DOMContentLoaded', function() {
            const savedView = localStorage.getItem('categoryView') || 'table';
            toggleView(savedView);
        });

        // Auto-hide success messages
        const successMessages = document.querySelectorAll('[role="alert"]');
        successMessages.forEach(message => {
            if (message.textContent.toLowerCase().includes('success')) {
                setTimeout(() => {
                    message.style.transition = 'opacity 0.5s ease-out, transform 0.5s ease-out';
                    message.style.opacity = '0';
                    message.style.transform = 'translateY(-10px)';
                    setTimeout(() => message.remove(), 500);
                }, 5000);
            }
        });

        // 💡 NEW: Modal Toggle and Data Population
        const editModal = document.getElementById('edit-category-modal');
        const addModal = document.getElementById('add-category-modal');
        const modalForm = document.getElementById('edit-category-form');
        const nameInput = document.getElementById('edit_category_name');
        // const slugInput = document.getElementById('edit_category_slug'); // Adjust to your actual slug field name



        function openEditModal(category) {

            modalForm.action = `/AdminDashboard/categories/${category.id}`;


            console.log(category);
            nameInput.value = category.category_name || '';
            // slugInput.value = category.category_slug || ''; // Adjust to your actual slug field name

            editModal.classList.remove('hidden');
            setTimeout(() => {
                editModal.querySelector('.modal-content').classList.remove('opacity-0', 'translate-y-4',
                    'sm:translate-y-0', 'sm:scale-95');
                editModal.querySelector('.modal-content').classList.add('opacity-100', 'translate-y-0',
                    'sm:scale-100');
            }, 50);
        }

        function openAddModal() {

            addModal.classList.remove('hidden');
            setTimeout(() => {
                addModal.querySelector('.modal-content').classList.remove('opacity-0', 'translate-y-4',
                    'sm:translate-y-0', 'sm:scale-95');
                addModal.querySelector('.modal-content').classList.add('opacity-100', 'translate-y-0',
                    'sm:scale-100');
            }, 50);
        }

        function closeEditModal() {
            editModal.querySelector('.modal-content').classList.remove('opacity-100', 'translate-y-0', 'sm:scale-100');
            editModal.querySelector('.modal-content').classList.add('opacity-0', 'translate-y-4', 'sm:translate-y-0',
                'sm:scale-95');
            setTimeout(() => {
                editModal.classList.add('hidden');
            }, 300);
        }

        function closeAddModal() {
            addModal.querySelector('.modal-content').classList.remove('opacity-100', 'translate-y-0', 'sm:scale-100');
            addModal.querySelector('.modal-content').classList.add('opacity-0', 'translate-y-4', 'sm:translate-y-0',
                'sm:scale-95');
            setTimeout(() => {
                addModal.classList.add('hidden');
            }, 300);
        }
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !editModal.classList.contains('hidden')) {
                closeEditModal();
                closeAddModal();
            }
        });
    </script>
</x-app-layout>

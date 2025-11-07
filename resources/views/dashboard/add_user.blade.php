<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add User') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Page Heading -->
                    <div class="flex items-center justify-between mb-6">
                        <h1 class="text-2xl font-bold text-gray-900">Add New User</h1>
                        <a href="{{ route('admin.dashboard') }}"
                            class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900 font-medium text-sm transition-colors duration-200">
                            <i class="fa-solid fa-arrow-left"></i>
                            <span>Back to Dashboard</span>
                        </a>
                    </div>

                    <!-- Form -->
                    <form action="{{ route('dashboard.adduser') }}" method="POST" enctype="multipart/form-data"
                        class="space-y-6">
                        @csrf

                        <!-- Name Field -->
                        <div>
                            <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                                Full Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 outline-none"
                                placeholder="Enter full name">
                            @error('name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email Field -->
                        <div>
                            <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                                Email Address <span class="text-red-500">*</span>
                            </label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 outline-none"
                                placeholder="user@example.com">
                            @error('email')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password Field -->
                        <div>
                            <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                                Password <span class="text-red-500">*</span>
                            </label>
                            <input type="password" id="password" name="password" required
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 outline-none"
                                placeholder="Enter password">
                            @error('password')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-2 text-xs text-gray-500">Password must be at least 8 characters long</p>
                        </div>

                        <!-- Confirm Password Field -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">
                                Confirm Password <span class="text-red-500">*</span>
                            </label>
                            <input type="password" id="password_confirmation" name="password_confirmation" required
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 outline-none"
                                placeholder="Re-enter password">
                        </div>
                        <div class="form-group">
                            <label for="designation" class="block text-sm font-semibold text-gray-700 mb-2">Role</label>
                            <select name="designation" id="designation"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 outline-none"
                                required>
                                <option value="user" selected>User</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>


                        <!-- File Upload Field -->
                        <div>
                            <label for="file" class="block text-sm font-semibold text-gray-700 mb-2">
                                Upload File (Optional)
                            </label>
                            <div class="relative">
                                <input type="file" id="file" name="file"
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 outline-none file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 file:cursor-pointer">
                                @error('file')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <p class="mt-2 text-xs text-gray-500">Accepted formats:JPG,JPEG, PNG (Max: 5MB)</p>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center gap-4 pt-4 border-t border-gray-200">
                            <button type="submit"
                                class="flex-1 sm:flex-initial inline-flex items-center justify-center gap-2 bg-gradient-to-r from-indigo-500 to-purple-600 text-white px-8 py-3 rounded-lg font-semibold text-sm shadow-lg shadow-indigo-500/30 hover:shadow-xl hover:shadow-indigo-500/40 hover:-translate-y-0.5 transition-all duration-200">
                                <i class="fa-solid fa-user-plus"></i>
                                <span>Create User</span>
                            </button>

                            <a href="{{ url('dashboard') }}"
                                class="flex-1 sm:flex-initial inline-flex items-center justify-center gap-2 bg-gray-100 text-gray-700 px-8 py-3 rounded-lg font-semibold text-sm hover:bg-gray-200 transition-all duration-200">
                                <i class="fa-solid fa-xmark"></i>
                                <span>Cancel</span>
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Info Card -->
            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-start gap-3">
                    <i class="fa-solid fa-circle-info text-blue-600 mt-0.5"></i>
                    <div>
                        <h3 class="text-sm font-semibold text-blue-900 mb-1">Important Information</h3>
                        <ul class="text-sm text-blue-800 space-y-1">
                            <li>• All fields marked with <span class="text-red-500">*</span> are required</li>
                            <li>• The user will receive a welcome email at the provided address</li>
                            <li>• Password must meet minimum security requirements</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

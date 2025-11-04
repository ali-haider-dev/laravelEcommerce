@props(['activeTab' => 'signin', 'errors' => new \Illuminate\Support\MessageBag()])

<div 
    x-data="{ 
        show: @json($errors->count() > 0), 
        activeTab: '{{ $activeTab }}', 
        toggleTab(tab) { this.activeTab = tab; } 
    }"
    @keydown.escape.window="show = false"
    @signin-modal.window="show = true"
    class="relative z-50">

    {{-- Modal Backdrop --}}
    <div x-show="show" @click="show = false"
         class="fixed inset-0 bg-gray-900 bg-opacity-75"
         x-transition></div>

    {{-- Modal Dialog --}}
    <div x-show="show" x-trap.inert="show"
         class="fixed inset-0 overflow-y-auto flex items-center justify-center p-4"
         x-transition>
        <div @click.stop class="relative bg-white rounded-lg shadow-xl w-full max-w-lg p-6">
            <button @click="show = false" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>

            {{-- Tabs --}}
            <div class="text-center mb-6">
                <ul class="flex border-b border-gray-200">
                    <li class="flex-1">
                        <a href="#" @click.prevent="toggleTab('signin')"
                           :class="activeTab === 'signin' ? 'border-b-2 border-indigo-600 text-indigo-600' : 'text-gray-500 hover:text-gray-700'"
                           class="block py-2 text-sm font-medium">
                            Sign In
                        </a>
                    </li>
                    <li class="flex-1">
                        <a href="#" @click.prevent="toggleTab('register')"
                           :class="activeTab === 'register' ? 'border-b-2 border-indigo-600 text-indigo-600' : 'text-gray-500 hover:text-gray-700'"
                           class="block py-2 text-sm font-medium">
                            Register
                        </a>
                    </li>
                </ul>
            </div>

            {{-- Sign In Form --}}
            <div x-show="activeTab === 'signin'" class="space-y-4">
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    @if ($errors->has('email') && $errors->first('email') !== 'The email must be a valid email address.')
                        <p class="text-sm text-red-500 bg-red-100 p-2 rounded">{{ $errors->first('email') }}</p>
                    @endif

                    <div>
                        <label for="signin-email" class="block text-sm font-medium text-gray-700">Email address *</label>
                        <input id="signin-email" type="email" name="email" value="{{ old('email') }}" required
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-200">
                    </div>

                    <div>
                        <label for="signin-password" class="block text-sm font-medium text-gray-700">Password *</label>
                        <input id="signin-password" type="password" name="password" required
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-200">
                        @error('password') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex justify-between items-center mt-4">
                        <label class="flex items-center text-sm text-gray-700">
                            <input type="checkbox" name="remember" class="mr-2 rounded text-indigo-600 border-gray-300">
                            Remember Me
                        </label>
                        <a href="{{ route('password.request') }}" class="text-sm text-indigo-600 hover:text-indigo-900">
                            Forgot Password?
                        </a>
                    </div>

                    <button type="submit" class="w-full mt-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md">
                        LOG IN
                    </button>
                </form>
            </div>

            {{-- Register Form --}}
            <div x-show="activeTab === 'register'" class="space-y-4">
                <form method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">First Name *</label>
                            <input type="text" name="first_name" value="{{ old('first_name') }}" required class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-200">
                            @error('first_name') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Last Name *</label>
                            <input type="text" name="last_name" value="{{ old('last_name') }}" required class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-200">
                            @error('last_name') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email *</label>
                        <input type="email" name="email" value="{{ old('email') }}" required class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-200">
                        @error('email') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Password *</label>
                        <input type="password" name="password" required class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-200">
                        @error('password') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Phone Number *</label>
                        <input type="text" name="phone_number" value="{{ old('phone_number') }}" required class="mt-1 w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-200">
                        @error('phone_number') <p class="text-sm text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-start mt-4">
                        <input type="checkbox" name="policy" required class="h-4 w-4 text-indigo-600 border-gray-300 rounded">
                        <label class="ml-2 text-sm text-gray-900">
                            I agree to the <a href="#" class="text-indigo-600 hover:text-indigo-900">privacy policy</a> *
                        </label>
                    </div>

                    <button type="submit" class="w-full mt-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md">
                        SIGN UP
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const modalElement = document.querySelector('[x-data]');
    if (!modalElement) return;

    const hasErrors = {{ $errors->count() > 0 ? 'true' : 'false' }};
    if (hasErrors) {
        const errors = @json($errors->keys());
        const hasRegisterError = errors.some(key =>
            ['first_name', 'last_name', 'phone_number', 'policy'].includes(key)
        );
        const data = Alpine.$data(modalElement);
        data.activeTab = hasRegisterError ? 'register' : 'signin';
        data.show = true;
    }

    @if (session('status') === 'registered successfully' || session('status') === 'login successfully')
        setTimeout(() => window.location.reload(), 300);
    @endif
});
</script>

<x-app-layout>

<div class="max-w-xl mx-auto p-8 bg-white shadow-2xl rounded-xl mt-10">
    <h1 class="text-3xl font-extrabold text-gray-900 mb-6 border-b pb-2">
        Edit User: {{ $user->name }}
    </h1>


    <div class="flex flex-col items-center mb-8">
        <div class="relative w-32 h-32 rounded-full overflow-hidden border-4 border-indigo-500 shadow-xl">
            @if ($user->profile_document_path)
            
                <img class="object-cover w-full h-full" src="{{ $user->profile_document_path ? asset('storage/' . $user->profile_document_path) : asset('images/default-avatar.png') }}" alt="{{ $user->name }}'s Avatar">

            @else
              
                <div class="flex items-center justify-center w-full h-full bg-indigo-100 text-indigo-600 text-6xl font-semibold">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
            @endif
        </div>
        <p class="mt-3 text-sm text-gray-500 text-center">Current Profile Image</p>
    </div>




    <form action="{{ route('dashboard.updateuser', $user->id) }}" method="POST" enctype="multipart/form-data">
        @csrf

        @method('PATCH') 


        <div class="mb-6">
            <label for="file" class="block text-sm font-medium  text-gray-700">Change Profile Image (Optional)</label>
            
            <input type="file" id="file" name="file" 
                   class="mt-1 self-center block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
            @error('file') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>


        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
            <input type="text" id="name" name="name" 
                   value="{{ old('name', $user->name) }}" 
                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-3 focus:ring-indigo-500 focus:border-indigo-500" 
                   required>
            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>


        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" id="email" name="email" 
                   value="{{ old('email', $user->email) }}" 
                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-3 focus:ring-indigo-500 focus:border-indigo-500" 
                   required>
            @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>


        <div class="mb-4">
            <label for="password" class="block text-sm font-medium text-gray-700">New Password (Optional)</label>
            <input type="password" id="password" name="password" 
                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-3 focus:ring-indigo-500 focus:border-indigo-500" 
                   placeholder="Leave blank to keep current password">
            @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>


        <div class="mb-6">
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" 
                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-3 focus:ring-indigo-500 focus:border-indigo-500">
        </div>

        <div class="flex justify-end space-x-4">
            <a href="{{ route('dashboard') }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition duration-150">
                Cancel
            </a>
            <button type="submit" 
                    class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150">
                Update User
            </button>
        </div>
    </form>
</div>

</x-app-layout>
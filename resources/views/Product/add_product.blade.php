<x-app-layout>
    <x-slot name="header">
        <h1 class="font-bold text-3xl leading-relaxed text-gray-600">Add Product</h1>
        <p class="text-sm font-semibold text-gray-600">Create a new product listing</p>
    </x-slot>
    @include('components.success')
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Page Heading -->
                    <div class="flex items-center justify-between mb-6">
                        <h1 class="text-2xl font-bold text-gray-900">Create New Product</h1>
                        <a href="{{ route('products') }}" 
                           class="inline-flex items-center gap-2 text-gray-600 hover:text-gray-900 font-medium text-sm transition-colors duration-200">
                            <i class="fa-solid fa-arrow-left"></i>
                            <span>Back to Products</span>
                        </a>
                    </div>

                    <!-- Form -->
                    <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <!-- Product Name -->
                        <div>
                            <label for="product_name" class="block text-sm font-semibold text-gray-700 mb-2">
                                Product Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="product_name" 
                                   name="product_name" 
                                   value="{{ old('product_name') }}"
                                   class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 outline-none"
                                   placeholder="Enter product name">
                            @error('product_name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Price and Category -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="price" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Price <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-semibold">$</span>
                                    <input type="number" 
                                           id="price" 
                                           name="price" 
                                           value="{{ old('price') }}"
                                           step="0.01"
                                           min="0"
                                           class="w-full pl-8 pr-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 outline-none"
                                           placeholder="0.00">
                                </div>
                                @error('price')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="category_id" class="block text-sm font-semibold text-gray-700 mb-2">
                                    Category <span class="text-red-500">*</span>
                                </label>
                                <select id="category_id" 
                                        name="category_id" 
                                  
                                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 outline-none">
                                    <option value="">Select Category</option>
                                    @foreach($categories ?? [] as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->category_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">
                                Description
                            </label>
                            <textarea id="description" 
                                      name="description" 
                                      rows="5"
                                      class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 outline-none resize-none"
                                      placeholder="Enter product description...">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Product Status Options -->
                        <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                            <h3 class="text-sm font-semibold text-gray-700 mb-3">Product Status</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="flex items-center">
                                    <input type="checkbox" 
                                           id="isHot" 
                                           name="isHot" 
                                           value="1"
                                           {{ old('isHot') ? 'checked' : '' }}
                                           class="w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-2 focus:ring-indigo-500">
                                    <label for="isHot" class="ml-3 text-sm font-semibold text-gray-700">
                                        <i class="fa-solid fa-fire text-orange-500 mr-1"></i>Hot Product
                                        <span class="block text-xs text-gray-500 font-normal mt-0.5">Featured as trending item</span>
                                    </label>
                                </div>

                                <div class="flex items-center">
                                    <input type="checkbox" 
                                           id="isActive" 
                                           name="isActive" 
                                           value="1"
                                           {{'checked'}}
                                           class="w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-2 focus:ring-indigo-500">
                                    <label for="isActive" class="ml-3 text-sm font-semibold text-gray-700">
                                        <i class="fa-solid fa-circle-check text-green-500 mr-1"></i>Active Status
                                        <span class="block text-xs text-gray-500 font-normal mt-0.5">Product visible to customers</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Attachments -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Product Attachments (Max 3 files)
                            </label>
                            
                            <input type="file" 
                                   id="attachments" 
                                   name="attachments[]" 
                                   multiple
                                   accept="image/*,.pdf,.doc,.docx"
                                   onchange="previewFiles(this)"
                                   class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 outline-none file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 file:cursor-pointer">
                            
                            <p class="mt-2 text-xs text-gray-500">
                                <i class="fa-solid fa-circle-info text-indigo-500 mr-1"></i>
                                Accepted: Images (JPG, PNG, GIF, WebP), PDF, DOC, DOCX | Max: 5MB per file | Total: 3 files
                            </p>
                            
                            @error('attachments')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            @error('attachments.*')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            
                            <!-- File Preview -->
                            <div id="file_preview" class="mt-4 hidden">
                                <p class="text-sm font-semibold text-gray-700 mb-3">Selected Files:</p>
                                <div id="preview_container" class="grid grid-cols-2 md:grid-cols-3 gap-4"></div>
                            </div>
                        </div>



                        <!-- Action Buttons -->
                        <div class="flex items-center gap-4 pt-6 border-t border-gray-200">
                            <button type="submit"
                                    class="flex-1 sm:flex-initial inline-flex items-center justify-center gap-2 bg-gradient-to-r from-indigo-500 to-purple-600 text-white px-8 py-3 rounded-lg font-semibold text-sm shadow-lg shadow-indigo-500/30 hover:shadow-xl hover:shadow-indigo-500/40 hover:-translate-y-0.5 transition-all duration-200">
                                <i class="fa-solid fa-plus"></i>
                                <span>Create Product</span>
                            </button>
                            
                            <a href="{{ route('products') }}"
                               class="flex-1 sm:flex-initial inline-flex items-center justify-center gap-2 bg-gray-100 text-gray-700 px-8 py-3 rounded-lg font-semibold text-sm hover:bg-gray-200 transition-all duration-200">
                                <i class="fa-solid fa-xmark"></i>
                                <span>Cancel</span>
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Help Card -->
            <div class="mt-6 bg-gradient-to-r from-indigo-50 to-purple-50 border border-indigo-200 rounded-lg p-6">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0">
                        <i class="fa-solid fa-lightbulb text-3xl text-indigo-600"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Product Creation Tips</h3>
                        <ul class="text-sm text-gray-700 space-y-2">
                            <li class="flex items-start gap-2">
                                <i class="fa-solid fa-check text-green-600 mt-1"></i>
                                <span>Use clear, descriptive product names that customers will search for</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <i class="fa-solid fa-check text-green-600 mt-1"></i>
                                <span>Set competitive pricing and include detailed descriptions</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <i class="fa-solid fa-check text-green-600 mt-1"></i>
                                <span>Upload high-quality images - the first image will be your primary display</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <i class="fa-solid fa-check text-green-600 mt-1"></i>
                                <span>Mark as "Hot Product" to feature it prominently on your store</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function previewFiles(input) {
            const files = Array.from(input.files);
            const previewContainer = document.getElementById('preview_container');
            const filePreview = document.getElementById('file_preview');
            
            // Validation
            if (files.length > 3) {
                alert('Maximum 3 files allowed!');
                input.value = '';
                filePreview.classList.add('hidden');
                return;
            }
            
            const maxSize = 5 * 1024 * 1024; // 5MB
            const oversizedFiles = files.filter(file => file.size > maxSize);
            if (oversizedFiles.length > 0) {
                alert('Some files exceed the 5MB limit!');
                input.value = '';
                filePreview.classList.add('hidden');
                return;
            }
            
            if (files.length > 0) {
                filePreview.classList.remove('hidden');
                previewContainer.innerHTML = '';
                
                files.forEach((file, index) => {
                    const reader = new FileReader();
                    const isImage = file.type.startsWith('image/');
                    const fileSize = (file.size / 1024).toFixed(2);
                    
                    // Create preview
                    if (isImage) {
                        reader.onload = function(e) {
                            const div = document.createElement('div');
                            div.className = 'relative group';
                            div.innerHTML = `
                                <img src="${e.target.result}" alt="${file.name}" class="w-full h-32 object-cover rounded-lg border-2 border-gray-300">
                                <div class=" rounded-lg transition-all duration-200"></div>
                                <div class="mt-2">
                                    <p class="text-xs text-gray-700 font-medium truncate">${file.name}</p>
                                    <p class="text-xs text-gray-500">${fileSize} KB</p>
                                </div>
                            `;
                            previewContainer.appendChild(div);
                        };
                        reader.readAsDataURL(file);
                    } else {
                        const div = document.createElement('div');
                        div.className = 'flex flex-col items-center';
                        div.innerHTML = `
                            <div class="w-full h-32 bg-gray-100 rounded-lg border-2 border-gray-300 flex flex-col items-center justify-center">
                                <i class="fa-solid fa-file text-3xl text-gray-600 mb-2"></i>
                                <p class="text-xs text-gray-600 text-center px-2">${file.type.split('/')[1]?.toUpperCase() || 'FILE'}</p>
                            </div>
                            <div class="mt-2 w-full">
                                <p class="text-xs text-gray-700 font-medium truncate">${file.name}</p>
                                <p class="text-xs text-gray-500">${fileSize} KB</p>
                            </div>
                        `;
                        previewContainer.appendChild(div);
                    }
                });
            } else {
                filePreview.classList.add('hidden');
            }
        }
    </script>
</x-app-layout>
<x-app-layout>
    <x-slot name="header">
        <h1 class="font-bold text-3xl leading-relaxed text-gray-600">Product List</h1>
        <p class="text-sm font-semibold text-gray-600">Overview of all the listing products</p>
    </x-slot>

    @include('components.success')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Page Heading -->
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Product List</h1>
                            <p class="text-sm text-gray-600 mt-1">Overview of all the listing products</p>
                        </div>
                        <a href="{{ route('product.add') }}"
                            class="inline-flex items-center gap-2 bg-gradient-to-r from-indigo-500 to-purple-600 text-white px-5 py-2.5 rounded-lg font-medium text-sm shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all duration-200">
                            <i class="fa-solid fa-plus"></i>
                            <span>Add Product</span>
                        </a>
                    </div>

                    <!-- Products Table -->
                    <div class="overflow-x-auto rounded-xl shadow-sm">
                        <table class="w-full border-collapse">
                            <thead class="bg-gradient-to-r from-indigo-500 to-purple-600">
                                <tr>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                        #</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                        Product Name</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                        Price</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                        Category</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($products as $product)
                                    <tr class="hover:bg-gray-50 transition-all duration-200 hover:shadow-sm">
                                        <td class="px-6 py-4 text-sm font-semibold text-gray-600">{{ $product->id }}
                                        </td>
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                            {{ $product->product_name }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-700">
                                            ${{ number_format($product->price, 2) }}</td>
                                        <td class="px-6 py-4 text-sm">
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $product->category->category_name ?? 'Uncategorized' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <button onclick="openViewModal({{ $product->toJson() }})"
                                                    class="w-9 h-9 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-all">
                                                    <i class="fa-solid fa-eye"></i>
                                                </button>

                                                <button onclick="openEditModal({{ $product->toJson() }})"
                                                    class="w-9 h-9 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-all">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </button>

                                                <form action="{{ route('product.delete', $product->id) }}"
                                                    method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button onclick="return confirm('Are you sure?')"
                                                        class="w-9 h-9 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition-all">
                                                        <i class="fa-solid fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-16 text-center text-gray-500">No products
                                            found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if (method_exists($products, 'links'))
                        <div
                            class="flex flex-col sm:flex-row justify-between items-center mt-6 border-t border-gray-200 pt-4">
                            <p class="text-sm text-gray-600">
                                Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} of
                                {{ $products->total() }}
                            </p>
                            {{ $products->links('pagination::bootstrap-4') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- View Modal -->
    <div id="viewModal"
        class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div
                class="flex justify-between items-center p-6 border-b bg-gradient-to-r from-indigo-500 to-purple-600 text-white">
                <h3 class="text-xl font-bold">Product Details</h3>
                <button onclick="closeViewModal()"><i class="fa-solid fa-xmark text-2xl"></i></button>
            </div>
            <div class="p-6 space-y-4">
                {{-- <p><strong>ID:</strong> <span id="v_id"></span></p> --}}
                <p><strong>Name:</strong> <span id="v_name"></span></p>
                <p><strong>Price:</strong> $<span id="v_price"></span></p>
                <p><strong>Category:</strong> <span id="v_category"></span></p>
                <p><strong>Hot:</strong> <span id="v_hot"></span></p>
                <p><strong>Active:</strong> <span id="v_active"></span></p>
                <p><strong>Description:</strong></p>
                <p id="v_description" class="bg-gray-50 p-3 rounded-lg"></p>
                <p><strong>Attachments:</strong></p>
                <div id="v_attachments" class="flex flex-wrap gap-3 bg-gray-50 p-3 rounded-lg"></div>
            </div>
            <div class="flex justify-end p-4 border-t">
                <button onclick="closeViewModal()"
                    class="px-5 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">Close</button>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal"
        class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div
                class="flex justify-between items-center p-6 border-b bg-gradient-to-r from-indigo-500 to-purple-600 text-white">
                <h3 class="text-xl font-bold">Edit Product</h3>
                <button onclick="closeEditModal()"><i class="fa-solid fa-xmark text-2xl"></i></button>
            </div>

            <form id="editForm" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label>Product Name</label>
                    <input type="text" name="product_name" id="e_name" required
                        class="w-full border rounded-lg p-2">
                </div>

                <div>
                    <label>Price</label>
                    <input type="number" step="0.01" name="price" id="e_price" required
                        class="w-full border rounded-lg p-2">
                </div>

                <div>
                    <label>Category</label>
                    <select name="category_id" id="e_category" class="w-full border rounded-lg p-2" required>
                        <option value="">Select Category</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->category->id }}">{{ $product->category->category_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label>Description</label>
                    <textarea name="description" id="e_description" rows="3" class="w-full border rounded-lg p-2"></textarea>
                </div>

                <div class="flex gap-4">
                    <label><input type="checkbox" id="e_hot" name="isHot" value="1"> Hot</label>
                    <label><input type="checkbox" id="e_active" name="isActive" value="1"> Active</label>
                </div>

                <div>
                    <label>Attachments (JSON array or upload)</label>
                    <input type="file" name="attachments[]" id="e_attachments" multiple
                        class="w-full border rounded-lg p-2">
                </div>

                <div class="flex justify-end border-t pt-4">
                    <button type="button" onclick="closeEditModal()"
                        class="px-5 py-2 bg-gray-600 text-white rounded-lg">Cancel</button>
                    <button type="submit" class="ml-3 px-5 py-2 bg-indigo-600 text-white rounded-lg">Update</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openViewModal(p) {
            // document.getElementById('v_id').textContent = p.id;
            document.getElementById('v_name').textContent = p.product_name;
            document.getElementById('v_price').textContent = parseFloat(p.price).toFixed(2);
            document.getElementById('v_category').textContent = p.category?.category_name || 'Uncategorized';
            document.getElementById('v_hot').textContent = p.isHot ? 'Yes' : 'No';
            document.getElementById('v_active').textContent = p.isActive ? 'Active' : 'Inactive';
            document.getElementById('v_description').textContent = p.description || 'No description';

            const cont = document.getElementById('v_attachments');
            cont.innerHTML = '';
            if (p.attachments && Array.isArray(p.attachments) && p.attachments.length) {
                p.attachments.forEach(url => {
                    const img = document.createElement('img');
                    img.src = "storage/" + url;
                    img.className = 'w-20 h-20 object-cover rounded-lg border';
                    cont.appendChild(img);
                    console.log("url=> ", img)
                });
            } else {
                cont.innerHTML = '<p class="text-gray-500 text-sm">No attachments</p>';
            }
            document.getElementById('viewModal').classList.remove('hidden');
        }

        function closeViewModal() {
            document.getElementById('viewModal').classList.add('hidden');
        }

        function openEditModal(p) {
            const f = document.getElementById('editForm');
            f.action = "{{ url('Products') }}/" + p.id;
            document.getElementById('e_name').value = p.product_name;
            document.getElementById('e_price').value = p.price;
            document.getElementById('e_category').value = p.category_id || '';
            document.getElementById('e_description').value = p.description || '';
            document.getElementById('e_hot').checked = !!p.isHot;
            document.getElementById('e_active').checked = !!p.isActive;
            document.getElementById('editModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') {
                closeViewModal();
                closeEditModal();
            }
        });
    </script>

</x-app-layout>

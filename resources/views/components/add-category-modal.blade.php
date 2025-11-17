<div id="add-category-modal" class="hidden fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog"
    aria-modal="true">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"
            onclick="closeAddModal()"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div
            class="modal-content inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95 duration-300">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div
                        class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-purple-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                            </path>
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-bold text-gray-900" id="modal-title">
                            Add Category
                        </h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500">
                                Enter the new category name. The changes will be applied immediately.
                            </p>

                            <form id="add-category-form" method="POST"
                                action="{{ route('categories.store') }}" class="mt-4">
                                @csrf
                                @method('POST') <div class="mb-4">
                                    <label for="add_category_name"
                                        class="block text-sm font-medium text-gray-700 mb-1">Category Name</label>
                                    <input type="text" name="category_name" id="add_category_name" required
                                        class="block w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors">
                                </div>

                                {{-- <div class="mb-4">
                                    <label for="edit_category_slug" class="block text-sm font-medium text-gray-700 mb-1">Category Slug</label>
                                    <input 
                                        type="text" 
                                        name="category_slug" 
                                        id="edit_category_slug" 
                                        required
                                        class="block w-full px-4 py-2 border border-gray-300 rounded-xl bg-gray-50 text-gray-500 cursor-not-allowed"
                                        readonly
                                    >
                                    <p class="mt-1 text-xs text-gray-500">Slug is typically generated from the name and cannot be changed here.</p>
                                </div> --}}

                                {{-- You can add other fields here (e.g., description, icon) --}}

                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 p-3 sm:px-6 gap-5 sm:flex sm:flex-row-reverse rounded-b-2xl">
                <button type="submit"
                    class="w-full inline-flex justify-center items-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-purple-600 text-base font-medium text-white hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                    Create Category
                </button>
                <button type="button" onclick="CloseAddModal()"
                    class="w-full inline-flex justify-center items-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                    Cancel
                </button>
                </form>
            </div>
        </div>
    </div>
</div>

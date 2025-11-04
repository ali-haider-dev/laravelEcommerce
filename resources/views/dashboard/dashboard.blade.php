<x-app-layout>
    <div id="alert-container" class="mt-4 self-end "></div>
    <x-slot name="header">
      <h1 class="font-bold text-3xl leading-relaxed text-gray-600">Admin Dashboard</h1>
        <p class="text-sm font-semibold text-gray-600">Overview of all the App users</p>
    </x-slot>

    <style>
        .pagination {
            gap: 0.5rem;
            display: flex;
        }

        .pagination .page-item .page-link {
            border-radius: 8px;
            padding: 0.5rem 0.75rem;
            color: #4b5563;
            font-weight: 500;
            transition: all 0.2s ease-in-out;
        }

        .pagination .page-item.active .page-link {
            background-color: #4f46e5;
            color: white;
            border-color: #4f46e5;
        }

        .pagination .page-item .page-link:hover {
            background-color: #e5e7eb;
            color: #111827;
        }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Page Heading -->
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                        <h1 class="text-2xl font-bold text-gray-900">Users Management</h1>
                        <a href="{{ route('dashboard.getuser') }}"
                            class="inline-flex items-center gap-2 bg-gradient-to-r from-indigo-500 to-purple-600 text-white px-5 py-2.5 rounded-lg font-medium text-sm shadow-lg shadow-indigo-500/30 hover:shadow-xl hover:shadow-indigo-500/40 hover:-translate-y-0.5 transition-all duration-200">
                            <i class="fa-solid fa-user-plus"></i>
                            <span>Add User</span>
                        </a>
                    </div>

                    <!-- Users Table -->
                    <div class="overflow-x-auto rounded-xl shadow-sm">
                        <table class="w-full border-collapse">
                            <thead class="bg-gradient-to-r from-indigo-500 to-purple-600">
                                <tr>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                        #</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                        Name</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                        Email</th>
                                    <th
                                        class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($users as $user)
                                    <tr id="user-row-{{ $user->id }}" class="hover:bg-gray-50 transition-all duration-200 hover:shadow-sm">
                                        <td class="px-6 py-4 text-sm font-semibold text-gray-600">
                                            {{ $loop->iteration + ($users->firstItem() - 1) }}
                                        </td>
                                        <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                            {{ $user->name }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-700">
                                            {{ $user->email }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <a href="{{ route('dashboard.edituser', $user->id) }}"
                                                    class="flex items-center justify-center w-9 h-9 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 hover:-translate-y-0.5 transition-all duration-200"
                                                    title="Edit User">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </a>

                                                <a href="#" data-user-id="{{ $user->id }}"
                                                   
                                                    class="delete-user-btn flex items-center justify-center w-9 h-9 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 hover:-translate-y-0.5 transition-all duration-200"
                                                    title="Delete User">

                                                    <i class="fa-solid fa-trash"></i>

                                                </a>

                                                @if ($user->file_name)
                                                    <a href="{{ url('functions/download_file?name=' . urlencode($user->file_name)) }}"
                                                        onclick="return confirm('Are you sure you want to download the file?');"
                                                        class="flex items-center justify-center w-9 h-9 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 hover:-translate-y-0.5 transition-all duration-200"
                                                        title="Download File">
                                                        <i class="fa-solid fa-download"></i>
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-16 text-center">
                                            <i class="fa-solid fa-users-slash text-5xl text-gray-300 mb-4"></i>
                                            <p class="text-base font-medium text-gray-600 mb-2">No users found</p>
                                            <p class="text-sm text-gray-500">Get started by adding your first user</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div
                        class="flex flex-col-reverse sm:flex-row justify-between items-start sm:items-center gap-4 mt-6 pt-4 border-t border-gray-200">
                        <p class="text-sm text-gray-600">
                            Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }}
                            results
                        </p>
                        <div>
                            {{ $users->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<script>
    document.addEventListener('DOMContentLoaded', function() {
      
      
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        const deleteButtons = document.querySelectorAll('.delete-user-btn');
        const alertContainer = document.getElementById('alert-container');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();

                const userId = this.getAttribute('data-user-id');
                const rowId = `user-row-${userId}`;

                if (confirm(`Are you sure you want to delete user ID ${userId}?`)) {

                    const deleteUrl = `/AdminDashboard/${userId}`;

                    fetch(deleteUrl, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken, 
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => {
                            if (!response.ok) {
                                return response.json().then(data => {
                                    throw new Error(data.message ||
                                        `HTTP error! Status: ${response.status}`
                                        );
                                });
                            }
                            return response.json();
                        })
                        .then(data => {
                            // 2. Success Logic
                            if (data.success) {
                                // Remove the row from the table
                                const rowToRemove = document.getElementById(rowId);
                                if (rowToRemove) {
                                    rowToRemove.remove();
                                }

                                // Display the success message
                                showAlert(data.message, 'success');
                            } else {
                                // Handle logic if the API returns a 200 but success=false
                                showAlert(data.message || 'Deletion failed.', 'error');
                            }
                        })
                        .catch(error => {
                            // 3. Error Logic
                            console.error('Fetch Error:', error);
                            showAlert(`An error occurred: ${error.message}`, 'error');
                        });
                }
            });
        });

        // Helper function to display alerts (optional, but good for UX)
        function showAlert(message, type) {
            alertContainer.innerHTML = ''; // Clear previous alerts
            const alertHtml = `
                <div class="p-4 rounded-lg text-white ${type === 'success' ? 'bg-green-500' : 'bg-red-500'}">
                    ${message}
                </div>
            `;
            alertContainer.innerHTML = alertHtml;

            // Auto-hide after 5 seconds
            setTimeout(() => {
                alertContainer.innerHTML = '';
            }, 5000);
        }
    });
</script>

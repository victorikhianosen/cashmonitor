@extends('layouts.admin.app')

@section('admin-section')
    <div id="users-container" class="p-4">
        <div class="flex justify-between items-center mb-4">
            <h4 class="text-slate-900 dark:text-slate-200 text-lg font-semibold">Users</h4>
            <button id="openModalBtn"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 transition text-sm">
                + Add User
            </button>
        </div>

        <div class="overflow-x-auto bg-white dark:bg-slate-800 shadow rounded-lg">
            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                <thead class="bg-blue-600 text-white dark:bg-slate-700">
                    <tr>
                        <th class="px-4 py-4 text-left text-xs font-semibold uppercase tracking-wider">User ID</th>
                        <th class="px-4 py-4 text-left text-xs font-semibold uppercase tracking-wider">Name</th>
                        <th class="px-4 py-4 text-left text-xs font-semibold uppercase tracking-wider">Company</th>
                        <th class="px-4 py-4 text-left text-xs font-semibold uppercase tracking-wider">Branch</th>
                        <th class="px-4 py-4 text-left text-xs font-semibold uppercase tracking-wider">Email</th>
                        <th class="px-4 py-4 text-left text-xs font-semibold uppercase tracking-wider">Role</th>
                        <th class="px-4 py-4 text-left text-xs font-semibold uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody id="users-table-body" class="divide-y divide-slate-200 dark:divide-slate-700">
                    @forelse($users as $user)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50">
                            <td class="px-4 py-3 text-sm text-slate-900 dark:text-slate-100">
                                {{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}
                            </td>
                            <td class="px-4 py-3 text-sm text-slate-900 dark:text-slate-100">{{ $user->name }}</td>
                            <td class="px-4 py-3 text-sm text-slate-900 dark:text-slate-100">{{ $user->company->name }}</td>
                            <td class="px-4 py-3 text-sm text-slate-900 dark:text-slate-100">{{ $user->branch->name }}</td>


                            <td class="px-4 py-3 text-sm text-slate-900 dark:text-slate-100">{{ $user->email }}</td>
                            <td
                                class="px-4 py-3 text-sm {{ $user->role === 'admin' ? 'text-green-600 dark:text-green-400' : ($user->role === 'manager' ? 'text-blue-600 dark:text-blue-400' : 'text-slate-900 dark:text-slate-100') }}">
                                {{ ucfirst($user->role) }}
                            </td>

                            <td class="px-4 py-3 text-sm space-x-2 flex">
                                <a href="{{ route('admin.user.edit', $user->id) }}"
                                    onclick="openEditUserModal({{ $user->id }}, '{{ $user->name }}', '{{ $user->email }}', '{{ $user->role }}', {{ $user->company_id }}, {{ $user->branch_id }})"
                                    class="px-2 py-1 bg-green-500 hover:bg-green-600 text-white rounded text-xs transition">Edit</a>

                                <a href="{{ route('admin.user.delete', $user->id) }}"
                                    class="px-2 py-1 bg-red-500 hover:bg-red-600 text-white rounded text-xs transition">Delete</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-3 text-center text-sm text-slate-500 dark:text-slate-400">No
                                users found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="px-4 pt-8 pb-4">
                <div class="flex justify-end items-center">
                    {{ $users->links('pagination::tailwind') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Add User Modal -->
    <div id="addUserModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-xl w-full max-w-sm p-4 relative">
            <h2 class="text-lg font-semibold text-slate-800 dark:text-slate-200 mb-3">Add User</h2>
            <form id="addUserForm" class="space-y-3">
                @csrf
                <div>
                    <label class="block mb-1 text-slate-700 dark:text-slate-300 text-sm" for="name">Name</label>
                    <input type="text" id="name" name="name" required
                        class="w-full border border-slate-300 dark:border-slate-600 rounded px-3 py-2 bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 text-sm">
                </div>

                <div>
                    <label class="block mb-1 text-slate-700 dark:text-slate-300 text-sm" for="email">Email</label>
                    <input type="email" id="email" name="email" required
                        class="w-full border border-slate-300 dark:border-slate-600 rounded px-3 py-2 bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 text-sm">
                </div>

                <div>
                    <label class="block mb-1 text-slate-700 dark:text-slate-300 text-sm" for="password">Password</label>
                    <input type="password" id="password" name="password" required
                        class="w-full border border-slate-300 dark:border-slate-600 rounded px-3 py-2 bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 text-sm">
                </div>

                <div>
                    <label class="block mb-1 text-slate-700 dark:text-slate-300 text-sm" for="role">Role</label>
                    <select id="role" name="role" required
                        class="w-full border border-slate-300 dark:border-slate-600 rounded px-3 py-2 bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 text-sm">
                        <option value="staff">staff</option>
                        <option value="manager">Manager</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>

                <div>
                    <label class="block mb-1 text-slate-700 dark:text-slate-300 text-sm" for="company_id">Company</label>
                    <select id="company_id" name="company_id" required
                        class="w-full border border-slate-300 dark:border-slate-600 rounded px-3 py-2 bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 text-sm">
                        <option value="">Select company</option>
                        @foreach ($companies as $company)
                            <option value="{{ $company->id }}">{{ $company->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block mb-1 text-slate-700 dark:text-slate-300 text-sm" for="branch_id">Branch</label>
                    <select id="branch_id" name="branch_id" required
                        class="w-full border border-slate-300 dark:border-slate-600 rounded px-3 py-2 bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 text-sm">
                        <option value="">Select branch</option>
                    </select>
                </div>

                <div class="flex justify-end space-x-2 pt-2">
                    <button type="button" id="closeModalBtn"
                        class="px-3 py-1.5 bg-gray-200 dark:bg-slate-600 text-slate-700 dark:text-slate-200 rounded hover:bg-gray-300 dark:hover:bg-slate-500 transition text-sm">Cancel</button>
                    <button type="submit"
                        class="px-3 py-1.5 bg-blue-600 text-white rounded hover:bg-blue-700 transition text-sm">Add</button>
                </div>
            </form>
        </div>
    </div>



    <script>
        const modal = document.getElementById('addUserModal');

        document.getElementById('openModalBtn').addEventListener('click', () => {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        });

        document.getElementById('closeModalBtn').addEventListener('click', () => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        });

        document.getElementById('company_id').addEventListener('change', async function() {
            const companyId = this.value;
            const branchSelect = document.getElementById('branch_id');
            branchSelect.innerHTML = '<option value="">Select branch</option>';

            if (!companyId) return;

            try {
                const res = await fetch(`/get-branches/${companyId}`);
                const data = await res.json();

                data.forEach(branch => {
                    const option = document.createElement('option');
                    option.value = branch.id;
                    option.textContent = branch.name;
                    branchSelect.appendChild(option);
                });
            } catch (error) {
                alert('Failed to load branches');
            }
        });

        document.getElementById('addUserForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const form = e.target;
            const formData = new FormData(form);

            try {
                const response = await fetch("{{ route('admin.user.store') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: formData
                });

                if (!response.ok) {
                    throw response;
                }

                const data = await response.json();
                toastr.success('User add suucessfully.');
                form.reset();
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                location.reload(); // Optional
            } catch (err) {
                let msg = 'Something went wrong';

                if (err.status === 422) {
                    const errorData = await err.json();
                    msg = Object.values(errorData.errors).flat().join('<br>');
                }

                toastr.error(data.message);
            }
        });
    </script>
@endsection

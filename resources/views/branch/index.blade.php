@extends('layouts.admin.app')

@section('admin-section')
    <div class="container mx-auto py-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold">Branches</h2>
            <div class="space-x-2">
                <a href="{{ route('admin.branch.index') }}"
                    class="bg-gray-300 text-black px-3 py-2 rounded text-sm">Refresh</a>
                <button onclick="openAddBranchModal()" class="bg-blue-600 text-white px-4 py-2 rounded text-sm">Add
                    Branch</button>
            </div>
        </div>

        <div class="bg-white dark:bg-blue-600 shadow rounded-lg overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-blue-600 text-white">
                    <tr>
                        <th class="px-4 py-2 text-left">#</th>
                        <th class="px-4 py-2 text-left">Name</th>
                        <th class="px-4 py-2 text-left"> Company</th> <!-- New -->

                        <th class="px-4 py-2 text-left">Account Number</th>
                        <th class="px-4 py-2 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($branches as $index => $branch)
                        <tr class="border-t">
                            <td class="px-4 py-2">
                                {{ ($branches->currentPage() - 1) * $branches->perPage() + $loop->iteration }}</td>
                            <td class="px-4 py-2">{{ $branch->name }}</td>
                            <td class="px-4 py-2">{{ $branch->company->name }}</td>
                            <td class="px-4 py-2">{{ $branch->account_number }}</td> <!-- New -->

                            <td class="px-4 py-2">
                                <div class="flex items-center gap-4">
                                    <button
                                        onclick="openEditBranchModal(
        {{ $branch->id }},
        '{{ addslashes($branch->name) }}',
        {{ $branch->company_id }},
        '{{ $branch->account_number }}'
    )"
                                        class="bg-blue-600 text-white py-1.5 px-3 rounded text-sm">
                                        Edit
                                    </button>


                                    <a href="{{ route('admin.branch.delete', $branch->id) }}"
                                        onclick="openEditBranchModal({{ $branch->id }}, '{{ $branch->name }}', {{ $branch->company_id }})"
                                        class="bg-red-600 text-white py-1.5 px-3 rounded text-sm">Delete</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4">No branches found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="px-4 pt-8 pb-4">
                <div class="flex justify-end items-center">
                    {{ $branches->links('pagination::tailwind') }}
                </div>
            </div>
        </div>
    </div>

    {{-- ADD BRANCH MODAL --}}
    <div id="addBranchModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-xl w-full max-w-sm p-4">
            <h2 class="text-lg font-semibold mb-3">Add Branch</h2>
            <form id="addBranchForm" class="space-y-3">
                @csrf
                <div>
                    <label class="block mb-1 text-sm">Name</label>
                    <input type="text" name="name" id="branch_name"
                        class="w-full border rounded px-3 py-2 bg-white dark:bg-slate-700 text-sm">
                    <span class="text-sm text-red-600 mt-1 block" id="error-branch-name"></span>
                </div>


                <div>
                    <label class="block mb-1 text-sm">Account Number</label>
                    <input type="text" name="account_number" id="account_number" maxlength="11" pattern="\d{10}"
                        inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0,10);"
                        placeholder="Enter 11-digit account number"
                        class="w-full border rounded px-3 py-2 bg-white dark:bg-slate-700 text-sm">
                    <span class="text-sm text-red-600 mt-1 block" id="error-account-number"></span>
                </div>


                <div>
                    <label class="block mb-1 text-sm">Company</label>
                    <select name="company_id" id="branch_company_id"
                        class="w-full border rounded px-3 py-2 bg-white dark:bg-slate-700 text-sm">
                        <option value="">Select Company</option>
                        @foreach ($companies as $company)
                            <option value="{{ $company->id }}">{{ $company->name }}</option>
                        @endforeach
                    </select>
                    <span class="text-sm text-red-600 mt-1 block" id="error-branch-company_id"></span>
                </div>

                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeAddBranchModal()"
                        class="px-3 py-1.5 bg-gray-300 rounded text-sm">Cancel</button>
                    <button type="submit" class="px-3 py-1.5 bg-blue-600 text-white rounded text-sm">Add</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Branch Modal -->
    <div id="editBranchModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-xl w-full max-w-sm p-4 relative">
            <h2 class="text-lg font-semibold text-slate-800 dark:text-slate-200 mb-4">Edit Branch</h2>

            <form id="editBranchForm">
                @csrf
                <input type="hidden" id="edit_branch_id" name="id">

                <div class="mb-4">
                    <label class="block mb-1 text-sm">Branch Name</label>
                    <input type="text" name="name" id="edit_branch_name"
                        class="w-full border rounded px-3 py-2 bg-white dark:bg-slate-700 text-sm">
                    <span class="text-sm text-red-600 mt-1 block" id="error-edit-branch-name"></span>
                </div>

                <div class="mb-4">
                    <label class="block mb-1 text-sm">Company</label>
                    <select name="company_id" id="edit_branch_company_id"
                        class="w-full border rounded px-3 py-2 bg-white dark:bg-slate-700 text-sm">
                        <option value="">-- Select Company --</option>
                        @foreach ($companies as $company)
                            <option value="{{ $company->id }}">{{ $company->name }}</option>
                        @endforeach
                    </select>
                    <span class="text-sm text-red-600 mt-1 block" id="error-edit-branch-company_id"></span>
                </div>


                <div class="mb-4">
                    <label class="block mb-1 text-sm">Account Number</label>
                    <input type="text" name="account_number" id="edit_account_number" maxlength="10" pattern="\d{10}"
                        inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0,10);"
                        placeholder="Enter 10-digit account number"
                        class="w-full border rounded px-3 py-2 bg-white dark:bg-slate-700 text-sm">
                    <span class="text-sm text-red-600 mt-1 block" id="error-edit-account-number"></span>
                </div>

                <div class="flex justify-end gap-2 mt-4">
                    <button type="button" onclick="closeEditBranchModal()"
                        class="px-4 py-2 bg-gray-300 text-sm rounded">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-sm rounded">Update</button>
                </div>
            </form>
        </div>
    </div>




    <script>
        // Open & Close Modals
        function openAddBranchModal() {
            document.getElementById('addBranchModal').classList.remove('hidden');
            document.getElementById('addBranchModal').classList.add('flex');
        }

        function closeAddBranchModal() {
            document.getElementById('addBranchModal').classList.add('hidden');
            document.getElementById('addBranchModal').classList.remove('flex');
        }

        function openEditBranchModal(id, name, companyId) {
            document.getElementById('edit_branch_id').value = id;
            document.getElementById('edit_branch_name').value = name;
            document.getElementById('edit_branch_company_id').value = companyId;
            document.getElementById('editBranchModal').classList.remove('hidden');
            document.getElementById('editBranchModal').classList.add('flex');
        }

        function closeEditBranchModal() {
            document.getElementById('editBranchModal').classList.add('hidden');
            document.getElementById('editBranchModal').classList.remove('flex');
        }

        // ADD BRANCH SUBMIT
        document.getElementById('addBranchForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = {
                name: document.getElementById('branch_name').value,
                company_id: document.getElementById('branch_company_id').value,
                account_number: document.getElementById('account_number').value
            };

            const response = await fetch(`{{ route('admin.branch.store') }}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(formData)
            });

            const data = await response.json();

            // Clear old errors
            document.getElementById('error-branch-name').textContent = '';
            document.getElementById('error-branch-company_id').textContent = '';
            document.getElementById('error-account-number').textContent = '';

            if (response.status === 422) {
                const errors = data.errors;
                if (errors.name) document.getElementById('error-branch-name').textContent = errors.name[0];
                if (errors.company_id) document.getElementById('error-branch-company_id').textContent = errors
                    .company_id[0];
                if (errors.account_number) document.getElementById('error-account-number').textContent = errors
                    .account_number[0];
            } else if (response.ok) {
                closeAddBranchModal();
                toastr.success('Branch created successfully');
                setTimeout(() => location.reload(), 1000);
            } else {
                toastr.error(data.message || 'Something went wrong.');
            }
        });
    </script>



    <script>
        function openEditBranchModal(id, name, companyId, accountNumber) {
            document.getElementById('edit_branch_id').value = id;
            document.getElementById('edit_branch_name').value = name;
            document.getElementById('edit_branch_company_id').value = companyId;
            document.getElementById('edit_account_number').value = accountNumber;
            document.getElementById('editBranchModal').classList.remove('hidden');
            document.getElementById('editBranchModal').classList.add('flex');
        }

        function closeEditBranchModal() {
            document.getElementById('editBranchModal').classList.add('hidden');
            document.getElementById('editBranchModal').classList.remove('flex');
        }

        document.getElementById('editBranchForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const id = document.getElementById('edit_branch_id').value;

            const formData = {
                name: document.getElementById('edit_branch_name').value,
                company_id: document.getElementById('edit_branch_company_id').value,
                account_number: document.getElementById('edit_account_number').value
            };

            // Clear previous errors
            document.getElementById('error-edit-branch-name').textContent = '';
            document.getElementById('error-edit-branch-company_id').textContent = '';
            document.getElementById('error-edit-account-number').textContent = '';

            const response = await fetch(`{{ route('admin.branch.update', ':id') }}`.replace(':id', id), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(formData)
            });

            const data = await response.json();

            if (response.status === 422) {
                const errors = data.errors;
                if (errors.name) {
                    document.getElementById('error-edit-branch-name').textContent = errors.name[0];
                }
                if (errors.company_id) {
                    document.getElementById('error-edit-branch-company_id').textContent = errors.company_id[0];
                }
                if (errors.account_number) {
                    document.getElementById('error-edit-account-number').textContent = errors.account_number[0];
                }
            } else if (response.ok) {
                closeEditBranchModal();
                toastr.success('Branch updated successfully');
                setTimeout(() => location.reload(), 1000);
            } else {
                toastr.error(data.message || 'Something went wrong.');
            }
        });
    </script>
@endsection

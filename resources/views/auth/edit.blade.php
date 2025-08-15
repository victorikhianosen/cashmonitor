@extends('layouts.admin.app')

@section('admin-section')
    <div id="users-container" class="p-4">
        <div class="flex flex-col justify-start items-start mb-4">
                        <a href="{{ route('admin.user') }}" class="text-sm">Back</a>
            <h4 class="text-slate-900 dark:text-slate-200 text-lg font-semibold">Edit User</h4>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-xl shadow-xl w-full max-w-sm p-6">
            <h2 class="text-lg font-semibold text-slate-800 dark:text-slate-200 mb-4">Edit User</h2>

            <form id="editUserForm" class="space-y-4" method="POST" action="{{ route('admin.user.update', $user->id) }}">
                @csrf

                <div>
                    <label class="block mb-1 text-sm">Name</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                        class="w-full border rounded px-3 py-2 text-sm">
                </div>

                <div>
                    <label class="block mb-1 text-sm">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                        class="w-full border rounded px-3 py-2 text-sm">
                </div>

                <div>
                    <label class="block mb-1 text-sm">Password <small>(Leave blank to keep unchanged)</small></label>
                    <input type="password" name="password" class="w-full border rounded px-3 py-2 text-sm">
                </div>

                <div>
                    <label class="block mb-1 text-sm">Role</label>
                    <select name="role" class="w-full border rounded px-3 py-2 text-sm">
                        <option value="staff" {{ $user->role === 'staff' ? 'selected' : '' }}>Staff</option>
                        <option value="manager" {{ $user->role === 'manager' ? 'selected' : '' }}>Manager</option>
                        <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                </div>


                <!-- Company Dropdown -->
                <div>
                    <label class="block mb-1 text-sm">Company</label>
                    <select name="company_id" id="companySelect" class="w-full border rounded px-3 py-2 text-sm">
                        <option>Select company</option>
                        @foreach ($companies as $company)
                            <option value="{{ $company->id }}" {{ $user->company_id == $company->id ? 'selected' : '' }}>
                                {{ $company->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Branch Dropdown -->
                <div>
                    <label class="block mb-1 text-sm">Branch</label>
                    <select name="branch_id" id="branchSelect" class="w-full border rounded px-3 py-2 text-sm">
                        <option>Select branch</option>
                        @foreach ($branches as $branch)
                            <option value="{{ $branch->id }}" {{ $user->branch_id == $branch->id ? 'selected' : '' }}>
                                {{ $branch->name }}
                            </option>
                        @endforeach
                    </select>
                </div>


                <div class="flex justify-end pt-4 gap-6">
                    <a href="" class="mr-2 px-4 py-2 bg-gray-300 rounded text-sm">Cancel</a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded text-sm">Update</button>
                </div>
            </form>
        </div>
    </div>





    <script>
        document.getElementById('companySelect').addEventListener('change', function() {
            const companyId = this.value;
            const branchSelect = document.getElementById('branchSelect');

            branchSelect.innerHTML = '<option value="">Loading...</option>';

            if (!companyId) {
                branchSelect.innerHTML = '<option value="">Select branch</option>';
                return;
            }

            fetch(`/get-branches/${companyId}`)
                .then(response => response.json())
                .then(data => {
                    branchSelect.innerHTML = '<option value="">Select branch</option>';
                    data.forEach(branch => {
                        const option = document.createElement('option');
                        option.value = branch.id;
                        option.textContent = branch.name;
                        branchSelect.appendChild(option);
                    });
                })
                .catch(error => {
                    branchSelect.innerHTML = '<option value="">Error loading branches</option>';
                    console.error('Fetch error:', error);
                });
        });
    </script>
@endsection

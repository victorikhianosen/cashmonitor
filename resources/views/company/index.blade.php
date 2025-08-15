@extends('layouts.admin.app')

@section('admin-section')
<div class="p-4">
    <div class="flex justify-between items-center mb-6">
        <h4 class="text-slate-900 dark:text-slate-200 text-lg font-semibold">Companies</h4>
        <div class="flex justify-center items-center gap-3">
            <button id="openAddCompany"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 transition text-sm">
                + Add Company
            </button>
        </div>
    </div>

    <div class="overflow-x-auto bg-white dark:bg-slate-800 shadow rounded-lg mb-8">
        <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
            <thead class="bg-blue-600 text-white">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">ID</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Name</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                @forelse($companies as $company)
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50">
                    <td class="px-4 py-3 text-sm text-slate-900 dark:text-slate-100">
                        {{ ($companies->currentPage() - 1) * $companies->perPage() + $loop->iteration }}
                    </td>
                    <td class="px-4 py-3 text-sm text-slate-900 dark:text-slate-100">
                        {{ $company->name }}
                    </td>
                
                    <td class="px-4 py-3 text-sm text-slate-900 dark:text-slate-100">
                        <div class="flex gap-2">
                            <button class="editCompanyBtn bg-blue-600 py-2 px-3 rounded-lg text-white"
                                data-id="{{ $company->id }}" data-name="{{ $company->name }}">
                                Edit
                            </button>
                            <a href="{{ route('admin.company.delete', $company->id) }}"
                                class="deleteCompanyBtn bg-red-600 py-2 px-3 rounded-lg text-white">
                                Delete
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-4 py-3 text-center text-sm text-slate-500 dark:text-slate-400">
                        No companies found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="px-4 pt-8 pb-4">
        <div class="flex justify-end items-center">
            {{ $companies->links('pagination::tailwind') }}
        </div>
    </div>
</div>

<!-- Add Company Modal -->
<div id="addCompanyModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-xl w-full max-w-sm p-4">
        <h2 class="text-lg font-semibold text-slate-800 dark:text-slate-200 mb-3">Add Company</h2>
        <form id="addCompanyForm" class="space-y-3">
            @csrf
            <div>
                <label class="block mb-1 text-slate-700 dark:text-slate-300 text-sm">Name</label>
                <input type="text" name="name" id="name"
                    class="w-full border rounded px-3 py-2 bg-white dark:bg-slate-700 text-sm">
                <span class="text-sm text-red-600 mt-1 block" id="error-name"></span>
            </div>
            <div class="flex justify-end space-x-2">
                <button type="button" id="closeAddCompany"
                    class="px-3 py-1.5 bg-gray-200 dark:bg-slate-600 rounded text-sm">Cancel</button>
                <button type="submit" class="px-3 py-1.5 bg-blue-600 text-white rounded text-sm">Add</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Company Modal -->
<div id="editCompanyModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-xl w-full max-w-sm p-4">
        <h2 class="text-lg font-semibold text-slate-800 dark:text-slate-200 mb-3">Edit Company</h2>
        <form id="editCompanyForm" class="space-y-3">
            @csrf
            <input type="hidden" id="edit_company_id">
            <div>
                <label class="block mb-1 text-slate-700 dark:text-slate-300 text-sm">Name</label>
                <input type="text" name="name" id="edit_name"
                    class="w-full border rounded px-3 py-2 bg-white dark:bg-slate-700 text-sm">
                <span class="text-sm text-red-600 mt-1 block" id="error-edit-name"></span>
            </div>
            <div class="flex justify-end space-x-2">
                <button type="button" id="closeEditCompany"
                    class="px-3 py-1.5 bg-gray-200 dark:bg-slate-600 rounded text-sm">Cancel</button>
                <button type="submit" class="px-3 py-1.5 bg-green-600 text-white rounded text-sm">Update</button>
            </div>
        </form>
    </div>
</div>

{{-- Scripts --}}
<script>
    // Open/Close Add Modal
    document.getElementById('openAddCompany').addEventListener('click', () => {
        document.getElementById('addCompanyModal').classList.remove('hidden');
        document.getElementById('addCompanyModal').classList.add('flex');
    });
    document.getElementById('closeAddCompany').addEventListener('click', () => {
        document.getElementById('addCompanyModal').classList.add('hidden');
        document.getElementById('addCompanyModal').classList.remove('flex');
    });

    // Add Company Form Submit
    document.getElementById('addCompanyForm').addEventListener('submit', async function (e) {
        e.preventDefault();
        document.getElementById('error-name').textContent = '';

        const formData = {
            name: document.getElementById('name').value,
        };

        try {
            const response = await fetch("{{ route('admin.company.store') }}", {
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
                if (data.errors.name) {
                    document.getElementById('error-name').textContent = data.errors.name[0];
                }
            } else if (response.ok) {
                toastr.success('✅ Company created successfully.');
                document.getElementById('addCompanyForm').reset();
                document.getElementById('addCompanyModal').classList.add('hidden');
                location.reload();
            } else {
                toastr.error(data.message || 'Unexpected error occurred.');
            }
        } catch (error) {
            console.error(error);
            toastr.error('Network error. Please try again.');
        }
    });

    // Open Edit Modal & Pre-fill data
    document.querySelectorAll('.editCompanyBtn').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.id;
            const name = btn.dataset.name;

            document.getElementById('edit_company_id').value = id;
            document.getElementById('edit_name').value = name;
            document.getElementById('error-edit-name').textContent = '';

            document.getElementById('editCompanyModal').classList.remove('hidden');
            document.getElementById('editCompanyModal').classList.add('flex');
        });
    });

    // Close Edit Modal
    document.getElementById('closeEditCompany').addEventListener('click', () => {
        document.getElementById('editCompanyModal').classList.add('hidden');
        document.getElementById('editCompanyModal').classList.remove('flex');
    });

    // Update Company Form Submit
    document.getElementById('editCompanyForm').addEventListener('submit', async function (e) {
        e.preventDefault();
        const id = document.getElementById('edit_company_id').value;

        const formData = {
            name: document.getElementById('edit_name').value,
        };

        try {
            const response = await fetch(`{{ route('admin.company.update', ':id') }}`.replace(':id', id), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(formData)
            });

            const data = await response.json();

            document.getElementById('error-edit-name').textContent = '';

            if (response.status === 422) {
                if (data.errors.name) {
                    document.getElementById('error-edit-name').textContent = data.errors.name[0];
                }
            } else if (response.ok) {
                toastr.success('✅ Company updated successfully.');
                document.getElementById('editCompanyModal').classList.add('hidden');
                location.reload();
            } else {
                toastr.error(data.message || 'Error updating company.');
            }
        } catch (error) {
            toastr.error('Network error. Please try again.');
        }
    });
</script>
@endsection

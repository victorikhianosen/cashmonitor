@extends('layouts.admin.app')

@section('admin-section')
<div id="transactions-container" class="p-4">
    <div class="flex justify-between items-center mb-4">
        <h4 class="text-slate-900 dark:text-slate-200 text-lg font-semibold">Transactions</h4>
        <span id="loading-indicator" class="text-sm text-slate-500 dark:text-slate-400 hidden">Loading...</span>
    </div>

    <div class="overflow-x-auto bg-white dark:bg-slate-800 shadow rounded-lg">
        <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
            <thead class="bg-slate-50 dark:bg-slate-700">
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-semibold uppercase text-slate-500 dark:text-slate-300">Transaction ID</th>
                    <th class="px-4 py-2 text-left text-xs font-semibold uppercase text-slate-500 dark:text-slate-300">Amount (₦)</th>
                    <th class="px-4 py-2 text-left text-xs font-semibold uppercase text-slate-500 dark:text-slate-300">Narration</th>
                    <th class="px-4 py-2 text-left text-xs font-semibold uppercase text-slate-500 dark:text-slate-300">Date</th>
                    <th class="px-4 py-2 text-left text-xs font-semibold uppercase text-slate-500 dark:text-slate-300">Type</th>
                    <th class="px-4 py-2 text-left text-xs font-semibold uppercase text-slate-500 dark:text-slate-300">Status</th>
                </tr>
            </thead>
            <tbody id="transactions-table-body" class="divide-y divide-slate-200 dark:divide-slate-700">
                <tr>
                    <td colspan="6" class="px-4 py-3 text-center text-sm text-slate-500 dark:text-slate-400">
                        Loading transactions...
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    hekl

    <div id="pagination-container" class="mt-6">
        <div id="pagination-info" class="text-sm text-slate-500 dark:text-slate-400 mb-2 text-center hidden"></div>
        <div id="pagination-controls" class="flex justify-center space-x-2"></div>
        Heehe
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const tbody = document.getElementById('transactions-table-body');
    const loadingIndicator = document.getElementById('loading-indicator');
    const paginationControls = document.getElementById('pagination-controls');
    const paginationInfo = document.getElementById('pagination-info');

    let currentPage = 1;

    async function fetchTransactions(page = 1) {
        try {
            loadingIndicator.classList.remove('hidden');
            const response = await axios.get(`/fetch-transactions?page=${page}`);
            const { transactions, pagination } = response.data;

            if (transactions.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="6" class="px-4 py-3 text-center text-sm text-slate-500 dark:text-slate-400">
                            No transactions found.
                        </td>
                    </tr>`;
                paginationControls.innerHTML = '';
                paginationInfo.classList.add('hidden');
                return;
            }

            tbody.innerHTML = transactions.map(tx => `
                <tr>
                    <td class="px-4 py-3 text-sm">${tx.transaction_id ?? '-'}</td>
                    <td class="px-4 py-3 text-sm">₦${parseFloat(tx.amount).toLocaleString('en-NG', { minimumFractionDigits: 2 })}</td>
                    <td class="px-4 py-3 text-sm truncate">${tx.narration ?? '-'}</td>
                    <td class="px-4 py-3 text-sm">${tx.date ?? '-'}</td>
                    <td class="px-4 py-3 text-sm">${tx.type ?? '-'}</td>
                    <td class="px-4 py-3 text-sm">${tx.status ?? '-'}</td>
                </tr>
            `).join('');

            updatePagination(pagination);
        } catch (error) {
            console.error(error);
            tbody.innerHTML = `
                <tr>
                    <td colspan="6" class="px-4 py-3 text-center text-sm text-red-500">
                        Error fetching transactions.
                    </td>
                </tr>`;
            paginationControls.innerHTML = '';
            paginationInfo.classList.add('hidden');
        } finally {
            loadingIndicator.classList.add('hidden');
        }
    }

    function updatePagination(pagination) {
        const { current_page, last_page, total, from, to } = pagination;
        paginationInfo.textContent = `Showing ${from} to ${to} of ${total} transactions`;
        paginationInfo.classList.remove('hidden');

        let buttons = '';
if (lastPage <= 1) {
    paginationControls.innerHTML = ''; // hide if truly 1
    console.log('Pagination hidden, lastPage:', lastPage);
    return;
}

        for (let i = 1; i <= last_page; i++) {
            buttons += `<button data-page="${i}" class="px-3 py-1 border rounded ${i === current_page ? 'bg-slate-800 text-white dark:bg-slate-200 dark:text-slate-800' : 'hover:bg-slate-200 dark:hover:bg-slate-600'}">${i}</button>`;
        }

        if (current_page < last_page) {
            buttons += `<button data-page="${current_page + 1}" class="px-3 py-1 border rounded hover:bg-slate-200 dark:hover:bg-slate-600">Next</button>`;
        }

        paginationControls.innerHTML = buttons;

        paginationControls.querySelectorAll('button').forEach(button => {
            button.addEventListener('click', () => {
                const page = parseInt(button.dataset.page);
                if (page !== currentPage) {
                    currentPage = page;
                    fetchTransactions(page);
                }
            });
        });
    }

    fetchTransactions();
});
</script>
@endsection

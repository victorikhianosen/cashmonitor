@extends('layouts.admin.app')

@section('admin-section')
    <div id="transactions-container" class="p-4" data-selected="{{ $selectedAccount }}">
        <div class="flex justify-between items-center mb-4">
            <h4 class="text-slate-900 dark:text-slate-200 text-lg font-semibold">Transactions</h4>

            <!-- Controls -->
            <div class="flex items-end gap-6">
                <!-- Select Account -->
                <div class="flex flex-col w-64">
                    <select id="account" name="account"
                        class="h-10 px-10 border border-gray-300 rounded-lg shadow-sm focus:outline-none
                               focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm
                               dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200 appearance-none"
                        @if($branches->count() <= 1) disabled @endif>
                        @foreach ($branches as $branch)
                            <option value="{{ $branch->account_number }}"
                                {{ $selectedAccount === $branch->account_number ? 'selected' : '' }}>
                                {{ $branch->name }} — {{ $branch->account_number }}
                            </option>
                        @endforeach
                    </select>

                    @if($branches->count() <= 1)
                        <span class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                            {{-- You can only view your branch. --}}
                        </span>
                    @endif
                </div>

                <!-- Refresh Button -->
                <button id="refreshBtn"
                    class="self-end inline-flex items-center justify-center h-10 px-6 rounded-lg bg-blue-600 text-white hover:bg-blue-800 transition">
                    Refresh
                </button>
            </div>
        </div>

        <div class="overflow-x-auto bg-white dark:bg-slate-800 shadow rounded-lg">
            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                <thead class="bg-blue-600 text-white dark:bg-slate-700">
                    <tr>
                        <th class="px-4 py-5 text-left text-xs font-semibold uppercase tracking-wider">S/N</th>
                        <th class="px-4 py-5 text-left text-xs font-semibold uppercase tracking-wider">Transaction ID</th>
                        <th class="px-4 py-5 text-left text-xs font-semibold uppercase tracking-wider">Amount</th>
                        <th class="px-4 py-5 text-left text-xs font-semibold uppercase tracking-wider">Narration</th>
                        <th class="px-4 py-5 text-left text-xs font-semibold uppercase tracking-wider">Date</th>
                        <th class="px-4 py-5 text-left text-xs font-semibold uppercase tracking-wider">Type</th>
                        <th class="px-4 py-5 text-left text-xs font-semibold uppercase tracking-wider">Status</th>
                    </tr>
                </thead>

                <tbody id="transactions-table-body" class="divide-y divide-slate-200 dark:divide-slate-700">
                    @if ($transactions && $transactions->count() > 0)
                        @foreach ($transactions as $index => $txn)
                            <tr>
                                <td class="px-4 py-2 text-sm text-slate-700 dark:text-slate-300">
                                    {{ $transactions->firstItem() + $index }}
                                </td>
                                <td class="px-4 py-2 text-sm text-slate-700 dark:text-slate-300">
                                    {{ $txn['transaction_id'] }}
                                </td>
                                <td class="px-4 py-2 text-sm text-slate-700 dark:text-slate-300">
                                    {{ $txn['amount_formatted'] ?? '₦' . number_format($txn['amount']) }}
                                </td>
                                <td class="px-4 py-2 text-sm text-slate-700 dark:text-slate-300">
                                    {{ $txn['narration'] }}
                                </td>
                                <td class="px-4 py-2 text-sm text-slate-700 dark:text-slate-300">
                                    {{ $txn['date_formatted'] ?? $txn['date'] }}
                                </td>
                                <td class="px-4 py-2 text-sm text-blue-600">
                                    {{ $txn['type'] }}
                                </td>
                                <td class="px-4 py-2 text-sm
                                    {{ $txn['status'] === 'Successful'
                                        ? 'text-green-600 dark:text-green-400'
                                        : ($txn['status'] === 'Pending'
                                            ? 'text-yellow-600 dark:text-yellow-400'
                                            : 'text-red-600 dark:text-red-400') }}">
                                    {{ $txn['status'] }}
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7" class="px-4 py-3 text-center text-sm text-red-600 dark:text-red-400">
                                No transactions available.
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        {{-- Pagination (server-rendered on first load; JS replaces after AJAX) --}}
        <div class="px-4 pt-8 pb-4">
            <div id="paginationMeta"
                class="flex justify-between items-center flex-wrap gap-2 text-sm text-slate-600 dark:text-slate-300">
                @if ($transactions)
                    <div>
                        Showing {{ $transactions->firstItem() }} to {{ $transactions->lastItem() }} of
                        {{ $transactions->total() }} results
                    </div>
                    <div id="paginationLinks">
                        {{ $transactions->appends(['account' => $selectedAccount])->links('vendor.pagination.simple-tailwind') }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- PRETTIER PRELOADER OVERLAY --}}
    <div id="pageLoader" class="fixed inset-0 z-[100] pointer-events-auto hidden" aria-live="polite" aria-busy="true" role="alert">
        <div class="absolute inset-0 bg-slate-900/20 dark:bg-slate-950/35 backdrop-blur-sm"></div>
        <div class="relative h-full w-full flex items-center justify-center p-6">
            <div class="w-full max-w-sm rounded-2xl shadow-xl border border-white/20 dark:border-white/10 bg-gray-200 dark:bg-slate-900/60 backdrop-blur-xl">
                <div class="h-1 w-full overflow-hidden rounded-t-2xl">
                    <div class="loader-bar h-full w-1/3 bg-blue-600/90"></div>
                </div>
                <div class="p-6 flex flex-col items-center text-center gap-4">
                    <svg class="h-6 w-6 animate-spin-slow text-blue-600" viewBox="0 0 24 24" fill="none">
                        <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-opacity=".25" stroke-width="3" />
                        <path d="M21 12a9 9 0 0 0-9-9" stroke="currentColor" stroke-width="3" stroke-linecap="round" />
                    </svg>
                    <div class="flex items-center gap-2">
                        <div class="text-center">
                            <p class="text-sm font-medium text-slate-700 dark:text-slate-200">Loading transactions</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Please wait…</p>
                        </div>
                    </div>
                    <div class="text-xs text-slate-500 dark:text-slate-400 animate-pulse">
                        Fetching latest data for your selected account
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="h-1.5 w-1.5 rounded-full bg-blue-600 animate-bounce [animation-delay:-.2s]"></span>
                        <span class="h-1.5 w-1.5 rounded-full bg-blue-600 animate-bounce"></span>
                        <span class="h-1.5 w-1.5 rounded-full bg-blue-600 animate-bounce [animation-delay:.2s]"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<style>
@keyframes spin-slow { to { transform: rotate(360deg) } }
.animate-spin-slow { animation: spin-slow 1.2s linear infinite }
.loader-bar { position: relative; left: -40%; animation: loader-slide 1.4s ease-in-out infinite; }
@keyframes loader-slide {
    0% { left: -40%; width: 30% }
    50% { left: 35%; width: 40% }
    100% { left: 110%; width: 30% }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const container      = document.getElementById('transactions-container');
    const accountSelect  = document.getElementById('account');
    const refreshBtn     = document.getElementById('refreshBtn');
    const tbody          = document.getElementById('transactions-table-body');
    const paginationMeta = document.getElementById('paginationMeta');
    const pageLoader     = document.getElementById('pageLoader');

    const endpoint = "{{ route('admin.dashboard.transactions') }}";
    let isLoading = false;

    function showLoading() {
        if (isLoading) return;
        isLoading = true;
        pageLoader.classList.remove('hidden');
        document.documentElement.style.overflow = 'hidden';
        accountSelect.disabled = true;
        refreshBtn.disabled = true;
        refreshBtn.classList.add('opacity-60', 'cursor-not-allowed');
    }

    function hideLoading() {
        isLoading = false;
        pageLoader.classList.add('hidden');
        document.documentElement.style.overflow = '';
        // Only re-enable if user has >1 branch
        if ({{ $branches->count() }} > 1) accountSelect.disabled = false;
        refreshBtn.disabled = false;
        refreshBtn.classList.remove('opacity-60', 'cursor-not-allowed');
    }

    async function fetchTransactions(account, page = 1) {
        try {
            showLoading();

            const url = new URL(endpoint, window.location.origin);
            url.searchParams.set('account', account);
            url.searchParams.set('page', page);

            const res = await fetch(url.toString(), {
                headers: { 'Accept': 'application/json' },
                credentials: 'same-origin'
            });

            if (!res.ok) return renderError("Unable to load transactions.");
            const json = await res.json();
            if (!json.ok) return renderError(json.error || "Unable to load transactions.");

            renderTable(json.data, json.meta);
        } catch (e) {
            renderError("Network error. Please try again.");
        } finally {
            hideLoading();
        }
    }

    function renderError(msg) {
        tbody.innerHTML = `
        <tr>
            <td colspan="7" class="px-4 py-3 text-center text-sm text-red-600 dark:text-red-400">
                ${msg}
            </td>
        </tr>`;
        if (paginationMeta) paginationMeta.innerHTML = '';
    }

    function statusClass(status) {
        if (status === 'Successful') return 'text-green-600 dark:text-green-400';
        if (status === 'Pending') return 'text-yellow-600 dark:text-yellow-400';
        return 'text-red-600 dark:text-red-400';
    }

    function renderTable(items, meta) {
        if (!items || items.length === 0) {
            renderError("No transactions available.");
            return;
        }

        const snStart = meta.from || 1;
        tbody.innerHTML = items.map((txn, idx) => `
            <tr>
                <td class="px-4 py-2 text-sm text-slate-700 dark:text-slate-300">${snStart + idx}</td>
                <td class="px-4 py-2 text-sm text-slate-700 dark:text-slate-300">${txn.transaction_id}</td>
                <td class="px-4 py-2 text-sm text-slate-700 dark:text-slate-300">${txn.amount_formatted}</td>
                <td class="px-4 py-2 text-sm text-slate-700 dark:text-slate-300">${txn.narration ?? ''}</td>
                <td class="px-4 py-2 text-sm text-slate-700 dark:text-slate-300">${txn.date_formatted}</td>
                <td class="px-4 py-2 text-sm text-blue-600">${txn.type}</td>
                <td class="px-4 py-2 text-sm ${statusClass(txn.status)}">${txn.status}</td>
            </tr>
        `).join('');

        const showing = `Showing ${meta.from} to ${meta.to} of ${meta.total} results`;
        const hasPrev = meta.current_page > 1;
        const hasNext = meta.current_page < meta.last_page;

        paginationMeta.innerHTML = `
            <div>${showing}</div>
            <div class="flex items-center gap-2" id="paginationLinks">
                <button id="pgPrev" class="px-3 py-1 border rounded ${hasPrev ? 'hover:bg-slate-100 dark:hover:bg-slate-700' : 'opacity-50 cursor-not-allowed'}" ${hasPrev ? '' : 'disabled'}>
                    Prev
                </button>
                <span>Page ${meta.current_page} / ${meta.last_page}</span>
                <button id="pgNext" class="px-3 py-1 border rounded ${hasNext ? 'hover:bg-slate-100 dark:hover:bg-slate-700' : 'opacity-50 cursor-not-allowed'}" ${hasNext ? '' : 'disabled'}>
                    Next
                </button>
            </div>
        `;

        const prevBtn = document.getElementById('pgPrev');
        const nextBtn = document.getElementById('pgNext');
        const account = accountSelect.value;

        if (prevBtn && hasPrev) prevBtn.addEventListener('click', () => fetchTransactions(account, meta.current_page - 1));
        if (nextBtn && hasNext) nextBtn.addEventListener('click', () => fetchTransactions(account, meta.current_page + 1));
    }

    // Account change -> fetch page 1 of that account
    accountSelect.addEventListener('change', () => {
        if (accountSelect.disabled) return; // staff
        const account = accountSelect.value;
        const url = new URL(window.location.href);
        url.searchParams.set('account', account);
        window.history.replaceState({}, '', url);
        fetchTransactions(account, 1);
    });

    // Refresh -> re-fetch current (page 1)
    refreshBtn.addEventListener('click', () => {
        const account = accountSelect.value;
        fetchTransactions(account, 1);
    });

    // Initial auto-load
    const initialAccount = container.getAttribute('data-selected') || accountSelect.value;
    if (initialAccount) {
        fetchTransactions(initialAccount, {{ request()->query('page', 1) }});
    }
});
</script>

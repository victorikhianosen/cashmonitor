@extends('layouts.admin.app')

@section('admin-section')
    <div id="transactions-container" class="p-4">
        <div class="flex justify-between items-center mb-4">
            <h4 class="text-slate-900 dark:text-slate-200 text-lg font-semibold">Transactions</h4>

            <div class="flex items-center gap-2">
              <a href="{{ route('admin.dashboard') }}"
   class="flex items-center gap-2 bg-blue-600 text-white px-4 py-2 text-sm rounded-md hover:bg-blue-700 transition">
    Refresh
</a>

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
                                {{-- Serial Number --}}
                                <td class="px-4 py-2 text-sm text-slate-700 dark:text-slate-300">
                                    {{ $transactions->firstItem() + $index }}
                                </td>

                                {{-- Transaction ID --}}
                                <td class="px-4 py-2 text-sm text-slate-700 dark:text-slate-300">
                                    {{ $txn['transaction_id'] }}
                                </td>

                                {{-- Amount --}}
                                <td class="px-4 py-2 text-sm text-slate-700 dark:text-slate-300">
                                    ₦{{ number_format($txn['amount']) }}
                                </td>

                                {{-- Narration --}}
                                <td class="px-4 py-2 text-sm text-slate-700 dark:text-slate-300">
                                    {{ $txn['narration'] }}
                                </td>

                                {{-- Date --}}


                                @php
                                    $formattedDate = \Carbon\Carbon::createFromFormat(
                                        'd-m-Y h:i:a',
                                        $txn['date'],
                                    )->format('l, h:i A  -  d F Y');
                                @endphp

                                <td class="px-4 py-2 text-sm text-slate-700 dark:text-slate-300">
                                    {{ $formattedDate }}
                                </td>


                                {{-- Type --}}
                                <td class="px-4 py-2 text-sm text-blue-600">
                                    {{ $txn['type'] }}
                                </td>

                                {{-- Status --}}
                                <td
                                    class="px-4 py-2 text-sm {{ $txn['status'] === 'Successful'
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

        {{-- Pagination --}}

        <div class="px-4 pt-8 pb-4">
            <div class="flex justify-between items-center flex-wrap gap-2 text-sm text-slate-600 dark:text-slate-300">
                <div>
                    Showing {{ $transactions->firstItem() }} to {{ $transactions->lastItem() }} of
                    {{ $transactions->total() }} results
                </div>
                <div>
                    {{ $transactions->links('vendor.pagination.simple-tailwind') }}
                </div>
            </div>
        </div>



    </div>
@endsection

<script>
    function reloadTransactions() {
        const spinner = document.getElementById('spinner');
        const reloadText = document.getElementById('reload-text');
        const reloadBtn = document.getElementById('reload-btn');

        spinner.classList.remove('hidden');
        to
        reloadText.textContent = 'Loading...';
        reloadBtn.disabled = true;

        // Force reload the page to trigger fresh fetch
        window.location.reload();
    }
</script>

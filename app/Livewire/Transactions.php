<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class Transactions extends Component
{
    use WithPagination;

    public $transactions = [];

    protected $paginationTheme = 'tailwind'; // use Tailwind for pagination styling

    public function fetchTransactions()
    {
        $user = Auth::user();
        $accountNumber = $user->company->account_number;

        $response = Http::get('https://corebank.paysorta.com/api/v1/account/get-deposit-transactions', [
            'accountNumber' => $accountNumber
        ]);

        $transactions = $response->json('data') ?? [];

        // simulate pagination locally since API does not paginate:
        $perPage = 10;
        $page = $this->page ?? 1;
        $offset = ($page - 1) * $perPage;

        $this->transactions = collect($transactions)
            ->slice($offset, $perPage)
            ->values();

        $this->setPage($page); // retain page on refresh
        $this->total = count($transactions);
    }

    public function render()
    {
        $this->fetchTransactions();

        return view('livewire.transactions', [
            'transactions' => $this->transactions,
            'total' => $this->total ?? 0,
            // 'perPage' => 10,
        ]);
    }
}

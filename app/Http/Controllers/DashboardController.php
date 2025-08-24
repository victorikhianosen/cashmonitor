<?php

namespace App\Http\Controllers;

use App\Models\Branch; // <= make sure this model exists
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Return branches the user is allowed to see:
     * - admin:   all branches
     * - manager: branches under their company
     * - staff:   only their own branch
     *
     * Expected relationships:
     * - $user->company (hasOne/belongsTo)
     * - $user->branch  (belongsTo)
     * - Company::branches() (hasMany)
     */
    private function allowedBranches($user): \Illuminate\Support\Collection
    {
        // Role helpers (works with spatie/laravel-permission or a simple 'role' column)
        $isAdmin   = method_exists($user, 'hasRole')    ? $user->hasRole('admin')   : strcasecmp($user->role ?? '', 'admin')   === 0;
        $isManager = method_exists($user, 'hasRole')    ? $user->hasRole('manager') : strcasecmp($user->role ?? '', 'manager') === 0;

        if ($isAdmin) {
            // All branches across all companies
            return Branch::select('name', 'account_number')->orderBy('name')->get();
        }

        if ($isManager) {
            // Manager: branches under their company (if any)
            if ($user->company) {
                return $user->company->branches()->select('name', 'account_number')->orderBy('name')->get();
            }
            return collect(); // No company attached
        }

        // Staff: only their own branch
        if ($user->branch) {
            return collect([
                (object)[
                    'name'           => $user->branch->name,
                    'account_number' => $user->branch->account_number,
                ]
            ]);
        }

        return collect();
    }

    public function dashboard(Request $request)
    {
        $baseURL = env('BASEURL');
        $user    = Auth::user();

        $branches = $this->allowedBranches($user);

        // Selected account comes from query or defaults to the first allowed branch
        $selectedAccount = $request->query('account') ?? ($branches->first()->account_number ?? null);

        // Guard: ensure the selected account is among allowed
        if ($selectedAccount && !$branches->pluck('account_number')->contains($selectedAccount)) {
            $selectedAccount = $branches->first()->account_number ?? null;
        }

        // If nothing allowed, render empty page
        if (!$selectedAccount) {
            return view('dashboard.index', [
                'transactions'     => null,
                'branches'         => $branches,
                'selectedAccount'  => null,
            ]);
        }

        // Fetch transactions for the selected account
        $response = Http::get($baseURL . '/account/get-deposit-transactions', [
            'accountNumber' => $selectedAccount,
        ])->json();

        $perPage     = 40;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();

        if (($response['status'] ?? false) === true) {
            $data = collect($response['data']);

            $paginated = new LengthAwarePaginator(
                $data->forPage($currentPage, $perPage)->values(),
                $data->count(),
                $perPage,
                $currentPage,
                ['path' => url()->current(), 'query' => ['account' => $selectedAccount]]
            );

            // Decorate items for view
            $paginated->setCollection(
                $paginated->getCollection()->map(function ($txn) {
                    try {
                        $formatted = Carbon::createFromFormat('d-m-Y h:i:a', $txn['date'])
                            ->format('l, h:i A  -  d F Y');
                    } catch (\Throwable $e) {
                        $formatted = $txn['date'];
                    }
                    $txn['date_formatted']   = $formatted;
                    $txn['amount_formatted'] = '₦' . number_format($txn['amount']);
                    return $txn;
                })
            );

            return view('dashboard.index', [
                'transactions'    => $paginated,
                'branches'        => $branches,
                'selectedAccount' => $selectedAccount,
            ]);
        }

        // API error → render page with no transactions
        return view('dashboard.index', [
            'transactions'    => null,
            'branches'        => $branches,
            'selectedAccount' => $selectedAccount,
        ]);
    }

    /**
     * JSON endpoint for AJAX swapping when selecting another account / paginating
     * Authorizes account by the same rule as dashboard().
     */
    public function transactionsJson(Request $request)
    {
        $request->validate([
            'account' => ['required', 'string'],
            'page'    => ['nullable', 'integer', 'min:1'],
        ]);

        $user      = Auth::user();
        $branches  = $this->allowedBranches($user);
        $allowed   = $branches->pluck('account_number')->all();

        // Authorize the requested account
        $account = $request->query('account');
        if (!in_array($account, $allowed, true)) {
            return response()->json([
                'ok'    => false,
                'error' => 'Unauthorized account.',
            ], 403);
        }

        $baseURL = env('BASEURL');
        $perPage = 40;
        $page    = (int) $request->query('page', 1);

        $response = Http::get($baseURL . '/account/get-deposit-transactions', [
            'accountNumber' => $account,
        ])->json();

        if (($response['status'] ?? false) !== true) {
            return response()->json([
                'ok'    => false,
                'error' => 'Unable to fetch transactions',
            ], 400);
        }

        $all = collect($response['data']);

        $paginator = new LengthAwarePaginator(
            $all->forPage($page, $perPage)->values(),
            $all->count(),
            $perPage,
            $page,
            ['path' => route('admin.dashboard.transactions')]
        );

        $items = $paginator->getCollection()->map(function ($txn) {
            try {
                $formatted = \Carbon\Carbon::createFromFormat('d-m-Y h:i:a', $txn['date'])
                    ->format('l, h:i A  -  d F Y');
            } catch (\Throwable $e) {
                $formatted = $txn['date'];
            }

            return [
                'transaction_id'    => $txn['transaction_id'],
                'amount'            => $txn['amount'],
                'amount_formatted'  => '₦' . number_format($txn['amount']),
                'narration'         => $txn['narration'],
                'date'              => $txn['date'],
                'date_formatted'    => $formatted,
                'type'              => $txn['type'],
                'status'            => $txn['status'],
            ];
        });

        return response()->json([
            'ok'   => true,
            'data' => $items,
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page'    => $paginator->lastPage(),
                'per_page'     => $paginator->perPage(),
                'total'        => $paginator->total(),
                'from'         => $paginator->firstItem() ?? 0,
                'to'           => $paginator->lastItem() ?? 0,
            ],
        ]);
    }
}

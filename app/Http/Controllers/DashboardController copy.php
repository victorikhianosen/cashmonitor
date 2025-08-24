<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
class DashboardController extends Controller
{

    public function dashboard(Request $request)
    {
        $accountNumber = Auth::user()->branch->account_number;
        $baseURL = env('BASEURL');

        

        $response = Http::get($baseURL . '/account/get-deposit-transactions', [
            'accountNumber' => $accountNumber
        ])->json();

        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 40;

        if (isset($response['status']) && $response['status'] === true) {
            $data = collect($response['data']);
            $paginated = new LengthAwarePaginator(
                $data->forPage($currentPage, $perPage),
                $data->count(),
                $perPage,
                $currentPage,
                ['path' => url()->current()]
            );

            return view('dashboard.index', [
                'transactions' => $paginated
            ]);
        }

        return view('dashboard.index', [
            'transactions' => null
        ]);
    }



}

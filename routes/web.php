<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DashboardController;

Route::get('/', fn() => redirect()->route('login'))->name('home');

Route::get('login', [AuthController::class, 'showLogin'])->name('login');
Route::get('forgetpassword', [AuthController::class, 'showForgetPassword'])->name('forgetpassword');
Route::post('forgetpassword', [AuthController::class, 'forgetPassword'])->name('forgetpassword.store');

Route::get('resetpassword/{email}', [AuthController::class, 'showResetPassword'])->name('resetpassword');
Route::post('resetpassword', [AuthController::class, 'resetPassword'])->name('resetpassword.store');

Route::post('login', [AuthController::class, 'login'])->name('login.store');


Route::middleware('auth')->name('admin.')->group(function () {


    Route::get('dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

    Route::get('/dashboard/transactions', [DashboardController::class, 'transactionsJson'])
        ->name('dashboard.transactions');

    Route::middleware('admin')->group(function () {
        Route::get('users', [AuthController::class, 'users'])->name('user');
        Route::post('users/add', [AuthController::class, 'storeUser'])->name('user.store');
        Route::get('users/delete/{id}', [AuthController::class, 'deleteUser'])->name('user.delete');
        Route::get('/user/edit/{id}', [AuthController::class, 'editUser'])->name('user.edit');

        Route::post('/user/update/{id}', [AuthController::class, 'updateUser'])->name('user.update');

        Route::get('/get-branches/{company}', [AuthController::class, 'getBranchesByCompany']);
        Route::get('/fetch-transactions', [DashboardController::class, 'fetchTransactions']);

        Route::prefix('company')->controller(CompanyController::class)->group(function () {
            Route::get('/', 'company')->name('company');
            Route::post('add', 'storeCompany')->name('company.store');
            Route::post('update/{id}', 'updateCompany')->name('company.update');
            Route::get('delete/{id}', 'deleteCompany')->name('company.delete');
        });

        Route::prefix('branch')->name('branch.')->controller(BranchController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('store', 'store')->name('store');
            Route::post('update/{id}', 'update')->name('update');
            Route::get('delete/{id}', 'delete')->name('delete');
        });
    });




    Route::get('logout', [AuthController::class, 'logout'])->name('logout');
});

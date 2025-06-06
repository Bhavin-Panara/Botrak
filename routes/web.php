<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ReportController;
// use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PricePlansController;
use App\Http\Controllers\CompanyPricePlansController;

/*
 * --------------------------------------------------------------------------
 * Web Routes
 * --------------------------------------------------------------------------
 * 
 * Here is where you can register web routes for your application. These
 * routes are loaded by the RouteServiceProvider within a group which
 * contains the "web" middleware group. Now create something great!
 *
 */

Route::get('/', function () {
    if (Auth::check()) { return redirect()->route('dashboard'); }
    return view('auth.login');
})->name('login.show');

Route::post('login', [LoginController::class, 'LoginAuth'])->name('login.prosses');

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/')->with('success', 'You have been logged out successfully.');
})->name('logout');

/*Auth::routes();*/

Route::middleware('auth')->group(function () {
    // ---------------------- Dashboard ----------------------
    Route::get('/dashboard', function () { return view('dashboard'); })->name('dashboard');
    // Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

    // ---------------------- Users ----------------------
    Route::prefix('user')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('user.index');
    });

    // ---------------------- Assets ----------------------
    Route::prefix('asset')->group(function () {
        Route::get('/', [UserController::class, 'asset'])->name('asset.index');
    });
    
    // ---------------------- Organization ----------------------
    Route::prefix('organization')->group(function () {
        Route::get('/', [OrganizationController::class, 'asset'])->name('organization.index');
    });
    
    // ---------------------- Report ----------------------
    Route::prefix('report')->group(function () {
        Route::get('/', function () { return view('report.index'); })->name('report.index');
        Route::get('/asset_count', [ReportController::class, 'asset_count'])->name('report.asset_count');
    });

    // ---------------------- Invoice ----------------------
    Route::prefix('invoice')->group(function () {
        Route::get('/', [InvoiceController::class, 'index'])->name('invoice.index');
        Route::get('/show/{id}', [InvoiceController::class, 'show'])->name('invoice.show');
        Route::post('/mark_as_paid', [InvoiceController::class, 'mark_as_paid'])->name('invoice.mark_as_paid');
        Route::get('/download/{id}', [InvoiceController::class, 'download'])->name('invoice.download');
    });

    // ---------------------- Price Plans ----------------------
    Route::prefix('price_plans')->group(function () {
        Route::get('/', [PricePlansController::class, 'index'])->name('price_plans.index');
        Route::get('/create', function () { return view('price_plans.create'); })->name('price_plans.create');
        Route::post('/store', [PricePlansController::class, 'store'])->name('price_plans.store');
        Route::get('/edit/{id}', [PricePlansController::class, 'edit'])->name('price_plans.edit');
        Route::put('/update/{id}', [PricePlansController::class, 'update'])->name('price_plans.update');
        Route::delete('/destroy/{id}', [PricePlansController::class, 'destroy'])->name('price_plans.destroy');
    });

    // ---------------------- Company Price Plans ----------------------
    Route::prefix('company_priceplan')->group(function () {
        Route::get('/', [CompanyPricePlansController::class, 'index'])->name('company_priceplan.index');
        Route::get('/history/{id}', [CompanyPricePlansController::class, 'history'])->name('company_priceplan.history');
        Route::get('/create',[CompanyPricePlansController::class, 'create'])->name('company_priceplan.create');
        Route::post('/store', [CompanyPricePlansController::class, 'store'])->name('company_priceplan.store');
        Route::get('/edit/{id}', [CompanyPricePlansController::class, 'edit'])->name('company_priceplan.edit');
        Route::put('/update/{id}', [CompanyPricePlansController::class, 'update'])->name('company_priceplan.update');
        Route::delete('/destroy/{id}', [CompanyPricePlansController::class, 'destroy'])->name('company_priceplan.destroy');
        Route::post('/cancel_plan/{id}', [CompanyPricePlansController::class, 'cancel_plan'])->name('company_priceplan.cancel_plan');
        Route::get('/get_plan_details/{id}', [CompanyPricePlansController::class, 'get_plan_details'])->name('company_priceplan.get_plan_details');
        Route::get('/edit/get_plan_details/{id}', [CompanyPricePlansController::class, 'get_plan_details'])->name('company_priceplan.edit_get_plan_details');
        Route::post('/update_billing', [CompanyPricePlansController::class, 'update_billing'])->name('company_priceplan.update_billing');
    });
});

Route::get('home', [HomeController::class, 'index'])->name('home');
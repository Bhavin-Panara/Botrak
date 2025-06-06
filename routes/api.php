<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CompanyPricePlansController;
use App\Http\Controllers\Api\InvoiceController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// ---------------------- Company Price Plans ----------------------
Route::prefix('company_priceplan')->group(function () {
    Route::post('/user_plan_details', [CompanyPricePlansController::class, 'user_plan_details']);
    Route::post('/cancel_plan', [CompanyPricePlansController::class, 'cancel_plan']);
});

// ---------------------- Invoice ----------------------
Route::prefix('invoice')->group(function () {
    Route::post('/user_plan_invoice', [InvoiceController::class, 'user_plan_invoice']);
});
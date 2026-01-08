<?php

use Illuminate\Support\Facades\Route;
use Modules\Cashflow\App\Http\Controllers\CashflowController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/cashflow', [CashflowController::class, 'index'])->name('cashflowIndex');
// Route::Group(['prefix' => config('smartend.backend_path'), 'middleware' => ['auth'], 'namespace'=>"\Modules\Cashflow\App\Http\Controllers"], function () {
//     Route::get('/cashflow', [CashflowController::class, 'index'])->name('cashflowIndex');
//     Route::prefix('cashflow')->as('cashflow.')->group(function () {
//         Route::resource('currency',CurrencyController::class);
//         Route::resource('vendors',VendorController::class);
//         Route::get('vendor/get_table_data','VendorController@get_table_data')->name('get_table_data');

//         Route::resource('term',TermController::class);
//     });
// });

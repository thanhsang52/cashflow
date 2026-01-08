<?php
/*use Illuminate\Support\Facades\Route;
use Modules\Cashflow\App\Http\Controllers\CashflowController;
use Modules\Cashflow\App\Http\Controllers\VendorController;

Route::Group(['prefix' => config('smartend.backend_path'), 'middleware' => ['auth']], function () {
    Route::get('/cashflow', [CashflowController::class, 'index'])->name('cashflowIndex');
    Route::prefix('cashflow')->as('cashflow.')->group(function () {
        Route::resource('currency',CurrencyController::class);
        Route::resource('vendors',VendorController::class);
        Route::get('vendor/get_table_data','\Modules\Cashflow\App\Http\Controllers\VendorController@get_table_data');
    });
});*/
/*Route::Group(['prefix' => config('smartend.backend_path'), 'middleware' => ['auth'], 'namespace'=>"\Modules\Cashflow\App\Http\Controllers"], function () {
    Route::get('/cashflow', [CashflowController::class, 'index'])->name('cashflowIndex');
    Route::prefix('cashflow')->as('cashflow.')->group(function () {
        Route::resource('currency',CurrencyController::class);
        Route::resource('vendors',VendorController::class);
        Route::get('vendor/get_table_data','VendorController@get_table_data')->name('get_table_data');

        Route::resource('term',TermController::class);
    });
});*/
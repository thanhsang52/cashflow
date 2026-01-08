<?php

use App\Http\Controllers\Custom\CustomController;
use Illuminate\Support\Facades\Route;
use Modules\Cashflow\App\Http\Controllers\CashflowController;
use Modules\Cashflow\App\Http\Controllers\CurrencyController;
use Modules\Cashflow\App\Http\Controllers\VendorController;
use Modules\Cashflow\App\Http\Controllers\TermController;
// private route example ( require login ):
///// Route::Group(['prefix' => config('smartend.backend_path'), 'middleware' => ['auth']], function () {
    ///// Route::get('/custom-page', [CustomController::class, 'custom_page']);
///// });

// public route example:
///// Route::get('/custom-page', [CustomController::class, 'custom_page']);




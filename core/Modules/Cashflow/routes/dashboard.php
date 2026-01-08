<?php

use Illuminate\Support\Facades\Route;
use Modules\Cashflow\App\Http\Controllers\CashflowController;
use Modules\Cashflow\App\Http\Controllers\VendorController;
use Modules\Cashflow\App\Http\Controllers\TermController;
use Modules\Cashflow\App\Http\Controllers\IncomeController;
use Modules\Cashflow\App\Http\Controllers\ExpenseController;
use Modules\Cashflow\App\Http\Controllers\NoteController;
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



Route::prefix('cashflow')->as('cashflow.')->group(function () {
    Route::resource('currency',CurrencyController::class);
    Route::resource('vendors',VendorController::class);
    Route::get('vendor/get_table_data','VendorController@get_table_data')->name('get_vendor_table_data');
    Route::get('term/get_table_data','TermController@get_table_data')->name('get_term_table_data');
    Route::get('list_modal', 'TermController@list_modal')->name('term.list_modal');
    Route::resource('term',TermController::class);
    
	Route::post('term/multidelete', 'TermController@multidelete')->name('term.multidelete');
    //Contract Controller
    Route::get('contract/get_table_data','ContractController@get_table_data')->name('get_contract_table_data');
    Route::resource('contract','ContractController');
    Route::get('contract/term/destroy/{id}/contract/{contract_id}', 'ContractController@destroyTerm')->name('contract.destroyTerm');
    Route::get('contract/term/destroy/term_id/{term_id}/contract/{contract_id}', 'ContractController@destroyContractTerm')->name('contract.destroyContractTerm');
    Route::get('contract/duplicate/{id}', 'ContractController@duplicate')->name('contract.duplicate');
    Route::match(['get','post', 'PATCH'],'contract/term-setting/{contract_id}','ContractController@handleSetting')->name('contract.handleSetting');
    Route::match(['post'],'contract/sign/{contract_id}','ContractController@sign')->name('contract.sign');

    Route::get('contract_term/get_table_data','ContractTermController@get_table_data');
    Route::get('contract_term/index','ContractTermController@index')->name('contract_term.index');

    Route::resource('contract_term_level','ContractTermLevelController');
    //Route::delete('contract_term_level/contract_term_id/{contract_term_id}/level/{level}', 'ContractTermLevelController@destroy_level')->name('contract_term_level.destroy');

    Route::resource('contract_term_condition','ContractTermConditionController');
    Route::post('contract_term_condition/get_data_term_condition','ContractTermConditionController@get_data_term_condition')->name('contract_term_condition.get_data_term_condition');
    //Income Controller
    Route::get('income/get_table_data','IncomeController@get_table_data')->name('get_income_table_data');
    Route::resource('income',IncomeController::class);
    Route::post('income/update_status','IncomeController@update_status')->name('income.update_status');
    Route::get('income/calculate-amount/{id}','IncomeController@calculate_amount')->name('income.calculate_amount');

    //Expense Controller
    Route::get('expense/get_table_data','ExpenseController@get_table_data');
    Route::resource('expense','ExpenseController');

    //Invoice Controller
    Route::get('invoice/get_table_data','InvoiceController@get_table_data')->name('get_invoice_table_data');
    Route::resource('invoice','InvoiceController');
    Route::resource('invoice-detail','InvoiceDetailsController');

    //Account Controller
    Route::get('accounts/get_table_data','AccountController@get_table_data')->name('get_account_table_data');
	Route::resource('accounts','AccountController');
    //Utility Controller
	Route::match(['get', 'post'],'general_settings/{store?}', 'UtilityController@settings')->name('settings.update_settings');

    //Permission Controller
    Route::get('permission/control/{user_id?}', 'PermissionController@index')->name('permission.index');
    Route::post('permission/store', 'PermissionController@store')->name('permission.store');

    Route::resource('note',NoteController::class);
    Route::prefix('website')->as('website.')->group(function () {
        //website order Controller
        Route::get('order/get_table_data','OrderController@get_table_data')->name('get_order_table_data');
        Route::resource('order','OrderController');
        //Route::resource('order-detail','OrderLineController');
    });
    
});

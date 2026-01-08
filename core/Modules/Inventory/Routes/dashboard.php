<?php

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


use Modules\Inventory\Http\Controllers\InventoryController;
use Modules\Inventory\Http\Controllers\PromotionController;

//Route::prefix('admin-home')->middleware(['setlang:backend', 'adminglobalVariable','auth:admin'])->group(function () {
    /*-----------------------------------
        INVENTORY ROUTES
    ------------------------------------*/
    Route::group(['prefix'=>'inventory','as' => 'inventory.'], function () {
        Route::controller(InventoryController::class)->group(function (){
            Route::get('/', 'index')->name('all');
            Route::get('edit/{item}', 'edit')->name('edit');
            Route::post('update', 'update')->name('update'); // [===== ??? =====]
            Route::get('get_inventory_table_data', 'get_table_data')->name('get_inventory_table_data');
        
        });
        
    });
    Route::group(['prefix'=>'promotion','as' => 'promotion.'], function () {
        Route::controller(PromotionController::class)->group(function (){
            Route::get('/', 'index')->name('all');
            //Route::get('edit/{item}', 'edit')->name('edit');
            //Route::post('update', 'update')->name('update'); // [===== ??? =====]
            Route::get('get_promotion_table_data', 'get_table_data')->name('get_promotion_table_data');
            Route::get('detail/{id}', 'detail')->name('detail-modal');
        
        });
    });
//});

Route::group(['prefix' => 'pos', 'as' => 'pos.'], function () {
    Route::get('/', 'POSController@index')->name('index');
    Route::get('quick-view', 'POSController@quickView')->name('quick-view');
    Route::post('variant_price', 'POSController@variant_price')->name('variant_price');
    Route::post('add-to-cart', 'POSController@addToCart')->name('add-to-cart');
    Route::post('remove-from-cart', 'POSController@removeFromCart')->name('remove-from-cart');
    Route::post('cart-items', 'POSController@cartItems')->name('cart_items');
    Route::post('update-quantity', 'POSController@updateQuantity')->name('updateQuantity');
    Route::post('empty-cart', 'POSController@emptyCart')->name('emptyCart');
    Route::post('tax', 'POSController@updateTax')->name('tax');
    Route::post('discount', 'POSController@updateDiscount')->name('discount');
    Route::get('customers', 'POSController@getCustomers')->name('customers');
    Route::get('customer-balance', 'POSController@customerBalance')->name('customer-balance');
    Route::post('order', 'POSController@placeOrder')->name('order');
    Route::get('orders', 'POSController@orderList')->name('orders');
    Route::get('order-details/{id}', 'POSController@order_details')->name('order-details');
    Route::get('invoice/{id}', 'POSController@generateInvoice');
    Route::get('search-products','POSController@searchProduct')->name('search-products');
    Route::get('search-by-add','POSController@searchByAddProduct')->name('search-by-add');
    Route::get('search-crmcustomer-by-id','POSController@searchCRMCustomer')->name('search-crmcustomer-by-id');

    Route::post('coupon-discount', 'POSController@couponDiscount')->name('coupon-discount');
    Route::post('remove-coupon','POSController@removeCoupon')->name('remove-coupon');
    Route::get('change-cart','POSController@changeCart')->name('change-cart');
    Route::get('new-cart-id','POSController@newCartId')->name('new-cart-id');
    Route::get('clear-cart-ids','POSController@clearCartIds')->name('clear-cart-ids');
    Route::get('get-cart-ids','POSController@getCartIds')->name('get-cart-ids');

    Route::post('register-shift', 'POSController@registerShift')->name('register-shift');
    Route::post('close-shift', 'POSController@closeShift')->name('close-shift');
});
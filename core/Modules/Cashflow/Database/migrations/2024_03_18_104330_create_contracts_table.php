<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cashflow_contracts', function (Blueprint $table) {
            $table->id();
			$table->string('name');	
			//$table->string('number')->unique();
            $table->string('code')->unique();
            $table->bigInteger('vendor_id')->unsigned();
            $table->string('type',40);
            $table->string('effect_from')->nullable();	
            $table->string('effect_to')->nullable();	
            $table->string('base_value')->nullable();	
            $table->string('included_vat');		
            $table->string('reference')->nullable();
            $table->string('payment_term')->nullable();
            $table->string('new_store_sku_payment_days')->nullable();
            $table->tinyInteger('return')->default(0);	
            $table->string('near_expiry_product')->nullable();	
            $table->string('stock_slow_selling')->nullable();
            $table->string('discontinued_items')->nullable();	
            $table->string('customer_return')->nullable();	
            $table->string('minimun_shelf_life')->nullable();	
            $table->string('cost_price_changes')->nullable();		
            $table->text('attachment')->nullable();
            $table->text('note')->nullable();
			$table->bigInteger('created_user_id')->nullable();
			$table->bigInteger('updated_user_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cashflow_contracts');
    }
};

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
        Schema::create('cashflow_accounts', function (Blueprint $table) {
            $table->id();
			$table->string('name');
			$table->string('account_no')->nullable();
			$table->bigInteger('currency_id')->unsigned();
			$table->decimal('openning_balance',10,2);
			$table->string('contact_person')->nullable();
			$table->string('contact_email')->nullable();
			$table->text('note')->nullable();
            $table->timestamps();
			
			$table->foreign('currency_id')->references('id')->on('cashflow_currency')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cashflow_accounts');
    }
};

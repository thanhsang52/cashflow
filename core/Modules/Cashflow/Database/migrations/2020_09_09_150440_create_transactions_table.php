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
        Schema::create('cashflow_transactions', function (Blueprint $table) {
            $table->id();
            $table->date('trans_date');
            $table->bigInteger('account_id')->unsigned();
            $table->bigInteger('category_id')->unsigned();
			$table->string('type',10)->nullable();
            $table->string('dr_cr',2);
            $table->bigInteger('amount')->unsigned();
            $table->decimal('currency_rate', 15, 8);
            $table->bigInteger('customer_id')->unsigned()->nullable();
            $table->bigInteger('contract_term_id')->unsigned()->nullable();
            $table->bigInteger('payment_method_id')->unsigned();
            $table->string('reference')->nullable();
            $table->text('attachment')->nullable();
            $table->text('note')->nullable();
            $table->enum('status', ['pending', 'approved', 'submited', 'processed','completed','rejected','trashed']);
			$table->bigInteger('created_user_id')->nullable();
			$table->bigInteger('updated_user_id')->nullable();
            $table->timestamps();
			$table->bigInteger('approved_by')->nullable();
            $table->datetime('approved_at')->nullable();
			$table->foreign('account_id')->references('id')->on('accounts')->onDelete('set null');
			$table->foreign('category_id')->references('id')->on('cashflow_transaction_categories')->onDelete('cascade');
			$table->foreign('payment_method_id')->references('id')->on('cashflow_payment_methods')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cashflow_transactions');
    }
};

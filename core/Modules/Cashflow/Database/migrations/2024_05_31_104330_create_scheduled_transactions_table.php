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
        Schema::create('cashflow_transaction_schedule', function (Blueprint $table) {
            $table->integer('contract_term_id')->unsigned();
            $table->date('transaction_date');
            $table->primary(['contract_term_id', 'transaction_date']);
            $table->tinyInteger('status')->default(0);
            $table->timestamp('created_at')->useCurrent();
            $table->foreign('contract_term_id')->references('id')->on('cashflow_contract_term')->onDelete('cascade');
        });
    } 

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cashflow_transaction_schedule');
    }
};

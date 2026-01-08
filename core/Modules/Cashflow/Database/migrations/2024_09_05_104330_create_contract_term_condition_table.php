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
        Schema::create('cashflow_contract_term_condition', function (Blueprint $table) {
            $table->id();
            $table->integer('contract_term_id')->unsigned();
            $table->text('attributes')->nullable();
            $table->decimal('discount', 18, 2)->unsigned()->default(0);
            $table->integer('created_by')->nullable();
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
        Schema::dropIfExists('cashflow_contract_term_condition');
    }
};

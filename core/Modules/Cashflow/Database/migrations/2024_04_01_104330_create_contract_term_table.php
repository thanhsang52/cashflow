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
        Schema::create('cashflow_contract_term', function (Blueprint $table) {
            $table->increments('id');
            //$table->primary(['contract_id', 'term_id']);
            $table->bigInteger('contract_id')->unsigned()->index();
            $table->foreign('contract_id')->references('id')->on('cashflow_contracts')->onDelete('cascade');
            $table->bigInteger('term_id')->unsigned()->index();
            $table->text('note')->nullable();
            $table->tinyInteger('ordering')->default(0);
            $table->string('ref_num',125)->nullable();
            $table->string('billing_frequency',4)->default(0);
            $table->string('frequency_start_date')->nullable();
            $table->string('frequency_end_date')->nullable();
            $table->string('frequency_cycle',125)->nullable();
            $table->string('is_percentage',4)->default(0);
            $table->tinyInteger('type')->default(0);
            $table->decimal('term_value', 18, 2)->unsigned()->default(0);
            $table->foreign('term_id')->references('id')->on('cashflow_terms')->onDelete('cascade');
        });
    } 

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cashflow_contract_term');
    }
};

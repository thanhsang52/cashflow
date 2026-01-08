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
        Schema::create('cashflow_contract_term_level', function (Blueprint $table) {
            //$table->increments('id');
            $table->integer('contract_term_id')->unsigned();
            $table->tinyInteger('level')->default(1);
            $table->primary(['contract_term_id', 'level']);
            $table->decimal('target', 18, 2)->unsigned()->default(0);
            $table->decimal('value', 18, 2)->unsigned()->default(0);
            $table->timestamp('created_at')->useCurrent();;
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
        Schema::dropIfExists('cashflow_contract_term_level');
    }
};

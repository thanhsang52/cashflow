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
        Schema::create('cashflow_customers', function (Blueprint $table) {
            $table->id();
			$table->string('name');	
			$table->string('company_name')->nullable();
			$table->string('email')->unique();	
            $table->string('phone',50)->nullable();	
            $table->string('country')->nullable();	
            $table->string('city')->nullable();	
            $table->string('state')->nullable();	
            $table->string('zip')->nullable();	
            $table->text('address')->nullable();	
            $table->text('note')->nullable();	
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
        Schema::dropIfExists('cashflow_customers');
    }
};

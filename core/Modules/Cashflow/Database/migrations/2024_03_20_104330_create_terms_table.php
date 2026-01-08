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
        Schema::create('cashflow_terms', function (Blueprint $table) {
            $table->id();
			$table->string('name');	
            $table->string('code')->unique();
            $table->tinyInteger('status')->default(1);
            $table->string('credit_acc_no',20)->nullable();	
            $table->string('dedit_acc_no',20)->nullable();	
            $table->bigInteger('category_id')->unsigned();
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
        Schema::dropIfExists('cashflow_terms');
    }
};

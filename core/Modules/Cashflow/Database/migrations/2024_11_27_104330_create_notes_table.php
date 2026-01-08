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
        Schema::create('cashflow_notes', function (Blueprint $table) {
            $table->id();
            $table->text('content')->nullable();
            $table->string('type',120);
            $table->bigInteger('object_id')->unsigned();
            $table->integer('created_by_id')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->index('type');
        });
    } 

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cashflow_notes');
    }
};

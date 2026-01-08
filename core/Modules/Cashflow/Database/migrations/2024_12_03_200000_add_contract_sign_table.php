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
        Schema::table('cashflow_contracts', function (Blueprint $table) {
            $table->tinyInteger('signed')
                    ->after('note')
                    ->default(0);
        });
        Schema::table('cashflow_contracts', function (Blueprint $table) {
            $table->text('signature')->nullable()
                    ->after('signed')
                    ->default('');
        });
        Schema::table('cashflow_contracts', function (Blueprint $table) {
            $table->string('acceptance_firstname')->nullable()
                    ->after('signature')
                    ->default('');
        });
        Schema::table('cashflow_contracts', function (Blueprint $table) {
            $table->string('acceptance_lastname')->nullable()
                    ->after('acceptance_firstname')
                    ->default('');
        });
        Schema::table('cashflow_contracts', function (Blueprint $table) {
            $table->datetime('acceptance_date')->nullable()
                    ->after('acceptance_lastname')
                    ->default('');
        });
        Schema::table('cashflow_contracts', function (Blueprint $table) {
            $table->string('acceptance_ip')->nullable()
                    ->after('acceptance_date')
                    ->default('');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cashflow_contracts', function (Blueprint $table) {
            $table->dropColumn('signed');
        });
        Schema::table('cashflow_contracts', function (Blueprint $table) {
            $table->dropColumn('signature');
        });
        Schema::table('cashflow_contracts', function (Blueprint $table) {
            $table->dropColumn('acceptance_firstname');
        });
        Schema::table('cashflow_contracts', function (Blueprint $table) {
            $table->dropColumn('acceptance_lastname');
        });
        Schema::table('cashflow_contracts', function (Blueprint $table) {
            $table->dropColumn('acceptance_date');
        });
        Schema::table('cashflow_contracts', function (Blueprint $table) {
            $table->dropColumn('acceptance_ip');
        });
    }
};

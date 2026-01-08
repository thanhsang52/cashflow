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
        Schema::table('webmaster_settings', function (Blueprint $table) {
            $table->tinyInteger('cashflow_status')
                    ->after('cookie_policy_status')
                    ->default(0);
        });
        Schema::table('webmaster_permissions', function (Blueprint $table) {
            $table->tinyInteger('cashflow_status')
                    ->after('home_status')
                    ->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('webmaster_settings', function (Blueprint $table) {
            $table->dropColumn('cashflow_status');
        });
        Schema::table('webmaster_permissions', function (Blueprint $table) {
            $table->dropColumn('cashflow_status');
        });
    }
};

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTblChartAccountStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_chart_of_account', function (Blueprint $table) {
            $table->tinyInteger('account_protected')->comment("cannot be delete");

        });

        Schema::table('tbl_default_chart_account', function (Blueprint $table) {
            $table->tinyInteger('account_protected')->comment("cannot be delete");
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_chart_account', function (Blueprint $table) {
            //
        });
    }
}

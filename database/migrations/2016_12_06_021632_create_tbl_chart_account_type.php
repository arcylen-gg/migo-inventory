<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblChartAccountType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_chart_account_type', function (Blueprint $table) {
            $table->increments('chart_type_id');
            $table->string('chart_type_name');
            $table->string('chart_type_description', 1000);
            $table->tinyinteger('has_open_balance');
            $table->tinyinteger('chart_type_category');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_chart_account_type');
    }
}

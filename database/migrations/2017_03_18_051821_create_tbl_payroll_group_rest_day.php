<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblPayrollGroupRestDay extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_payroll_group_rest_day', function (Blueprint $table) {
            $table->increments('payroll_group_rest_day_id');
            $table->integer('payroll_group_id');
            $table->string('payroll_group_rest_day');
            $table->string('payroll_group_rest_day_category')->default('rest day');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_payroll_group_rest_day');
    }
}

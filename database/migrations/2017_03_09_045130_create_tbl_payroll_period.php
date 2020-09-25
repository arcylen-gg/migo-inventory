<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblPayrollPeriod extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_payroll_period', function (Blueprint $table) {
            $table->increments('payroll_period_id');
            $table->integer('shop_id');
            $table->date('payroll_period_start');
            $table->date('payroll_period_end');
            $table->string('payroll_period_category');
            $table->tinyInteger('payroll_period_archived');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_payroll_period');
    }
}

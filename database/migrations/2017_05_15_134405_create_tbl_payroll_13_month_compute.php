<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblPayroll13MonthCompute extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_payroll_13_month_compute', function (Blueprint $table) {
            $table->increments('payroll_13_month_compute_id');
            $table->integer('shop_id')->unsigned();
            $table->foreign('shop_id')->references('shop_id')->on('tbl_shop')->onDelete('cascade');
            $table->integer('payroll_employee_id')->unsigned();
            $table->foreign('payroll_employee_id')->references('payroll_employee_id')->on('tbl_payroll_employee_basic')->onDelete('cascade');
            $table->integer('payroll_period_company_id')->unsigned();
            $table->foreign('payroll_period_company_id')->references('payroll_period_company_id')->on('tbl_payroll_period_company')->onDelete('cascade');
            $table->integer('payroll_record_id')->unsigned();
            $table->foreign('payroll_record_id')->references('payroll_record_id')->on('tbl_payroll_record')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_payroll_13_month_compute');
    }
}

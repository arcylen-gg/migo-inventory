<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblPayroll13MonthVirtual extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_payroll_13_month_virtual', function (Blueprint $table) {
            $table->increments('payroll_13_month_virtual_id');
            $table->integer('payroll_employee_id')->unsigned();
            $table->foreign('payroll_employee_id')->references('payroll_employee_id')->on('tbl_payroll_13_month_compute')->onDelete('cascade');
            $table->integer('payroll_period_company_id')->unsigned();
            $table->foreign('payroll_period_company_id')->references('payroll_period_company_id')->on('tbl_payroll_period_company')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_payroll_13_month_virtual');
    }
}

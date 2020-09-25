<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblTimeKeepingApproved extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_payroll_time_keeping_approved', function (Blueprint $table)
        {
            $table->increments('time_keeping_approve_id');
            $table->integer('employee_id')->unsigned();
            $table->integer('payroll_period_company_id')->unsigned();
            $table->foreign('employee_id')->references('payroll_employee_id')->on('tbl_payroll_employee_basic')->onDelete('cascade');
            $table->foreign('payroll_period_company_id', 'tk_period_id_foreign')->references('payroll_period_company_id')->on('tbl_payroll_period_company')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}

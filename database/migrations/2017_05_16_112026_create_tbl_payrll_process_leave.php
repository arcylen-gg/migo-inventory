<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblPayrllProcessLeave extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_payroll_process_leave', function (Blueprint $table) {
            $table->increments('payroll_process_leave_id');

            $table->integer('payroll_employee_id')->unsigned();
            $table->foreign('payroll_employee_id')->references('payroll_employee_id')->on('tbl_payroll_employee_basic')->onDelete('cascade');

            $table->integer('payroll_period_company_id')->unsigned();
            $table->foreign('payroll_period_company_id')->references('payroll_period_company_id')->on('tbl_payroll_period_company')->onDelete('cascade');
           
            $table->integer('payroll_leave_temp_id')->unsigned();
            $table->foreign('payroll_leave_temp_id')->references('payroll_leave_temp_id')->on('tbl_payroll_leave_temp')->onDelete('cascade');

            $table->double('process_leave_quantity', 18, 2);
            $table->string('payroll_leave_temp_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_payroll_process_leave');
    }
}

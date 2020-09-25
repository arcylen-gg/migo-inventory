<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblPayrollRequestOvertime extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_payroll_request_overtime', function (Blueprint $table) 
        {
            $table->increments('payroll_request_overtime_id');
            $table->integer('payroll_employee_id')->unsigned();
            $table->foreign("payroll_employee_id")->references('payroll_employee_id')->on('tbl_payroll_employee_basic')->onDelete('cascade');
            $table->integer('payroll_approver_group_id')->unsigned();
            $table->foreign("payroll_approver_group_id", 'pag_id')->references('payroll_approver_group_id')->on('tbl_payroll_approver_group')->onDelete('cascade');
            $table->date('payroll_request_overtime_date');
            $table->string('payroll_request_overtime_type');
            $table->text('payroll_request_overtime_remark');
            $table->time('payroll_request_overtime_time_in');
            $table->time('payroll_request_overtime_time_out');
            $table->time('payroll_request_regular_time_in');
            $table->time('payroll_request_regular_time_out');
            $table->time('payroll_request_overtime_total_hours');
            $table->string('payroll_request_overtime_status')->default('pending');
            $table->integer('payroll_request_overtime_status_level')->default(0);
            $table->integer('archived');
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

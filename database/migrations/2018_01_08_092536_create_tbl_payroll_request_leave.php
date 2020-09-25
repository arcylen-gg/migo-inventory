<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblPayrollRequestLeave extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('tbl_payroll_request_leave', function (Blueprint $table) 
        {
            $table->increments('payroll_request_leave_id');
            $table->integer('payroll_employee_id')->unsigned();
            $table->foreign("payroll_employee_id")->references('payroll_employee_id')->on('tbl_payroll_employee_basic')->onDelete('cascade');
            $table->integer('payroll_approver_group_id')->unsigned();
            $table->foreign("payroll_approver_group_id", 'pagl_id')->references('payroll_approver_group_id')->on('tbl_payroll_approver_group')->onDelete('cascade');
            $table->integer('payroll_request_leave_id_reliever');
            $table->text('payroll_request_leave_type');
            $table->date('payroll_request_leave_date_filed');
            $table->date('payroll_request_leave_date');
            $table->time('payroll_request_leave_total_hours')->default('00:00');;
            $table->text('payroll_request_leave_remark');
            $table->string('payroll_request_leave_status')->default('pending');
            $table->integer('payroll_request_leave_status_level')->default(0);
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

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblPayrollLeaveSchedule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_payroll_leave_schedule', function (Blueprint $table) {
            $table->increments('payroll_leave_schedule_id');
            $table->integer('payroll_leave_employee_id')->unsigned();
            $table->foreign("payroll_leave_employee_id")->references("payroll_leave_employee_id")->on("tbl_payroll_leave_employee")->onDelete('cascade');
            $table->date('payroll_schedule_leave');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_payroll_leave_schedule');
    }
}

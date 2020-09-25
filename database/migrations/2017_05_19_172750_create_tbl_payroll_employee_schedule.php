<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblPayrollEmployeeSchedule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_payroll_employee_schedule', function (Blueprint $table) {
            $table->increments('employee_schedule_id');
            $table->integer('payroll_employee_id')->unsigned();
            $table->foreign('payroll_employee_id')->references('payroll_employee_id')->on('tbl_payroll_employee_basic')->onDelete('cascade');
            $table->date('schedule_date');
            $table->double('target_hours', 18,2);
            $table->time('work_start');
            $table->time('work_end');
            $table->time('break_start');
            $table->time('break_end');
            $table->tinyInteger('flexi');
            $table->tinyInteger('rest_day');
            $table->tinyInteger('extra_day');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_payroll_employee_schedule');
    }
}

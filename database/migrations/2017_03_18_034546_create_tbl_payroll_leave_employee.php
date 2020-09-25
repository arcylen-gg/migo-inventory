<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblPayrollLeaveEmployee extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('tbl_payroll_leave_employee', function (Blueprint $table) {
            $table->increments('payroll_leave_employee_id');
            $table->integer('payroll_leave_temp_id');
            $table->integer('payroll_employee_id');
            $table->tinyInteger('payroll_leave_employee_is_archived');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_payroll_leave_employee');   
     }
}

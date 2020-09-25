<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblPayrollLeaveEmployeeV2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
            Schema::create('tbl_payroll_leave_employee_v2', function (Blueprint $table) {
            $table->increments('payroll_leave_employee_id');
            $table->integer('payroll_leave_temp_id');
            $table->integer('payroll_employee_id');
            $table->decimal('payroll_leave_temp_hours', 4, 2);
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
        //
            Schema::drop('tbl_payroll_leave_employee_v2');   
    }
}

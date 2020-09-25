<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblPayrollDeductionEmployeeV2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('tbl_payroll_deduction_employee_v2', function (Blueprint $table) {
            $table->increments('payroll_deduction_employee_id');
            $table->integer('payroll_deduction_id');
            $table->integer('payroll_employee_id');
            $table->tinyInteger('payroll_deduction_employee_archived')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_payroll_deduction_employee_v2');
    }
}

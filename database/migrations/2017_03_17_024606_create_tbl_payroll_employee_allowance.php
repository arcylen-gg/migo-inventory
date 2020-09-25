<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblPayrollEmployeeAllowance extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_payroll_employee_allowance', function (Blueprint $table) {
            $table->increments('payroll_employee_allowance_id');
            $table->integer('payroll_allowance_id');
            $table->integer('payroll_employee_id');
            $table->tinyInteger('payroll_employee_allowance_archived');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_payroll_employee_allowance');
    }
}

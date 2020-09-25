<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblPayrollEmployeeContract extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_payroll_employee_contract', function (Blueprint $table) {
            $table->increments('payroll_employee_contract_id');
            $table->integer('payroll_employee_id');
            $table->integer('payroll_department_id');
            $table->integer('payroll_jobtitle_id');
            $table->date('payroll_employee_contract_date_hired');
            $table->date('payroll_employee_contract_date_end');
            $table->integer('payroll_employee_contract_status');
            $table->integer('payroll_group_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_payroll_employee_contract');
    }
}

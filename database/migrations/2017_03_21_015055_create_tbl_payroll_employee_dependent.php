<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblPayrollEmployeeDependent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_payroll_employee_dependent', function (Blueprint $table) {
            $table->increments('payroll_employee_dependent_id');
            $table->integer('payroll_employee_id');
            $table->string('payroll_dependent_name');
            $table->string('payroll_dependent_relationship');
            $table->date('payroll_dependent_birthdate');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_payroll_employee_dependent');
    }
}

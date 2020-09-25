<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblPayrollEmployeeSalary extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_payroll_employee_salary', function (Blueprint $table) {
            $table->increments('payroll_employee_salary_id');
            $table->integer('payroll_employee_id');
            $table->date('payroll_employee_salary_effective_date');
            $table->tinyInteger('payroll_employee_salary_minimum_wage')->default(0);
            $table->double('payroll_employee_salary_monthly', 18, 2)->default(0);
            $table->double('payroll_employee_salary_daily', 18, 2)->default(0);
            $table->double('payroll_employee_salary_taxable', 18, 2)->default(0);
            $table->double('payroll_employee_salary_sss', 18, 2)->default(0);
            $table->double('payroll_employee_salary_pagibig', 18, 2)->default(0);
            $table->double('payroll_employee_salary_philhealth', 18, 2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_payroll_employee_salary');
    }
}

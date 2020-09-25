<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblPayrollEmployeeBasic extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_payroll_employee_basic', function (Blueprint $table) {
            $table->increments('payroll_employee_id');
            $table->integer('shop_id');
            $table->integer('payroll_employee_company_id');
            $table->string('payroll_employee_title_name');
            $table->string('payroll_employee_first_name');
            $table->string('payroll_employee_middle_name');
            $table->string('payroll_employee_last_name');
            $table->string('payroll_employee_suffix_name');
            $table->string('payroll_employee_display_name');
            $table->string('payroll_employee_contact');
            $table->string('payroll_employee_email');
            $table->date('payroll_employee_birthdate');
            $table->string('payroll_employee_gender');
            $table->string('payroll_employee_number');
            $table->string('payroll_employee_atm_number');
            $table->string('payroll_employee_street');
            $table->string('payroll_employee_city');
            $table->string('payroll_employee_state');
            $table->string('payroll_employee_zipcode');
            $table->integer('payroll_employee_country');
            $table->string('payroll_employee_tax_status');
            $table->string('payroll_employee_tin');
            $table->string('payroll_employee_sss');
            $table->string('payroll_employee_pagibig');
            $table->string('payroll_employee_philhealth');
            $table->text('payroll_employee_remarks');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_payroll_employee_basic');
    }
}

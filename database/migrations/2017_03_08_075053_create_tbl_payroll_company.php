<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblPayrollCompany extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_payroll_company', function (Blueprint $table) {
            $table->increments('payroll_company_id');
            $table->integer('payroll_parent_company_id')->default(0);
            $table->string('payroll_company_name');
            $table->text('payroll_company_address');
            $table->string('payroll_company_contact');
            $table->string('payroll_company_email');
            $table->string('payroll_company_nature_of_business');
            $table->integer('payroll_company_rdo')->default(0);
            $table->date('payroll_company_date_started');
            $table->string('payroll_company_tin');
            $table->string('payroll_company_sss');
            $table->string('payroll_company_philhealth');
            $table->string('payroll_company_pagibig');
            $table->string('payroll_company_logo');
            $table->tinyInteger('payroll_company_archived');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_payroll_company');
    }
}

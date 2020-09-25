<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblPayrollPeriodCompany extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_payroll_period_company', function (Blueprint $table) {
            $table->increments('payroll_period_company_id');
            $table->integer('payroll_period_id');
            $table->integer('payroll_company_id');
            $table->string('payroll_period_status')->default('pending');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_payroll_period_company');
    }
}

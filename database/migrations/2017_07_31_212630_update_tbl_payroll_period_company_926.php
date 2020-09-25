<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblPayrollPeriodCompany926 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_payroll_period_company', function (Blueprint $table)
        {
            $table->double("payroll_period_total_basic");
            $table->double("payroll_period_total_gross");
            $table->double("payroll_period_total_net");
            $table->double("payroll_period_total_sss_ee");
            $table->double("payroll_period_total_sss_er");
            $table->double("payroll_period_total_sss_ec");
            $table->double("payroll_period_total_philhealth_ee");
            $table->double("payroll_period_total_philhealth_er");
            $table->double("payroll_period_total_pagibig_ee");
            $table->double("payroll_period_total_pagibig_er");
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
    }
}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblPayrollRecord extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_payroll_record', function (Blueprint $table) {
            $table->increments('payroll_record_id');
            $table->integer('shop_id');
            $table->integer('payroll_employee_id');
            $table->integer('payroll_period_company_id');
            $table->double('salary_monthly',18,2);
            $table->double('salary_daily',18,2);
            $table->double('regular_salary', 18, 2);
            $table->double('regular_early_overtime', 18, 2);
            $table->double('regular_reg_overtime', 18, 2);
            $table->double('regular_night_diff', 18, 2);
            $table->double('extra_salary', 18, 2);
            $table->double('extra_early_overtime', 18, 2);
            $table->double('extra_reg_overtime', 18, 2);
            $table->double('extra_night_diff', 18, 2);
            $table->double('rest_day_salary', 18, 2);
            $table->double('rest_day_early_overtime', 18, 2);
            $table->double('rest_day_reg_overtime', 18, 2);
            $table->double('rest_day_night_diff', 18, 2);
            $table->double('rest_day_sh', 18, 2);
            $table->double('rest_day_sh_early_overtime', 18, 2);
            $table->double('rest_day_sh_reg_overtime', 18, 2);
            $table->double('rest_day_sh_night_diff', 18, 2);
            $table->double('rest_day_rh', 18, 2);
            $table->double('rest_day_rh_early_overtime', 18, 2);
            $table->double('rest_day_rh_reg_overtime', 18, 2);
            $table->double('rest_day_rh_night_diff', 18, 2);
            $table->double('rh_salary', 18, 2);
            $table->double('rh_early_overtime', 18, 2);
            $table->double('rh_reg_overtime', 18, 2);
            $table->double('rh_night_diff', 18, 2);
            $table->double('sh_salary', 18, 2);
            $table->double('sh_early_overtime', 18, 2);
            $table->double('sh_reg_overtime', 18, 2);
            $table->double('sh_night_diff', 18, 2);
            $table->double('13_month', 18, 2);
            $table->tinyInteger('13_month_computed');
            $table->tinyInteger('minimum_wage');
            $table->string('tax_status');
            $table->double('salary_taxable',18,2);
            $table->double('salary_sss',18,2);
            $table->double('salary_pagibig',18,2);
            $table->double('salary_philhealth',18,2);
            $table->double('tax_contribution',18,2);
            $table->double('sss_contribution_ee',18,2);
            $table->double('sss_contribution_er',18,2);
            $table->double('sss_contribution_ec',18,2);
            $table->double('philhealth_contribution_ee',18,2);
            $table->double('philhealth_contribution_er',18,2);
            $table->double('pagibig_contribution',18,2);
            $table->double('late_deduction', 18, 2);
            $table->double('under_time', 18,2);
            $table->double('agency_deduction', 18,2);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_payroll_record');
    }
}

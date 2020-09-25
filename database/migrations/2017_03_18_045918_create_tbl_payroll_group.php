<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblPayrollGroup extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_payroll_group', function (Blueprint $table) {
            $table->increments('payroll_group_id');
            $table->integer('shop_id');
            $table->string('payroll_group_code');
            $table->string('payroll_group_salary_computation')->default('Daily');
            $table->string('payroll_group_period');
            $table->string('payroll_group_13month_basis');
            $table->tinyInteger('payroll_group_deduct_before_absences')->default(0);
            $table->string('payroll_group_tax');
            $table->string('payroll_group_sss');
            $table->string('payroll_group_philhealth');
            $table->string('payroll_group_pagibig');
            $table->string('payroll_group_agency');
            $table->double('payroll_group_agency_fee', 18, 2);
            $table->tinyInteger('payroll_group_is_flexi_time')->default(0)->comment('if the value is 1. it will consider true');
            $table->double('payroll_group_working_day_month', 18, 2);
            $table->string('payroll_group_target_hour_parameter')->default('Daily');
            $table->double('payroll_group_target_hour', 18, 2);
            $table->time('payroll_group_start');
            $table->time('payroll_group_end');
            $table->tinyInteger('payroll_group_archived')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_payroll_group');
    }
}

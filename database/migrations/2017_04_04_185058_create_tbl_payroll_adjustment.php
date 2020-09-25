<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblPayrollAdjustment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_payroll_adjustment', function (Blueprint $table) {
            $table->increments('payroll_adjustment_id');
            $table->integer('payroll_employee_id');
            $table->integer('payroll_period_company_id');
            $table->string('payroll_adjustment_name');
            $table->string('payroll_adjustment_category');
            $table->double('payroll_adjustment_amount', 18,2);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_payroll_adjustment');
    }
}

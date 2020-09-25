<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblForEmployeeAllowance3y473y57 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_payroll_allowance_v2', function (Blueprint $table) {
            $table->increments('payroll_allowance_id');
            $table->integer('shop_id');
            $table->string('payroll_allowance_name');
            $table->double('payroll_allowance_amount');
            $table->string('payroll_allowance_category')->default('fixed');
            $table->string('payroll_allowance_add_period')->default('Every Period');
            $table->tinyInteger('payroll_allowance_archived');
            $table->integer('expense_account_id');
            $table->string('payroll_allowance_type');
        });
        Schema::create('tbl_payroll_employee_allowance_v2', function (Blueprint $table) {
            $table->increments('payroll_employee_allowance_id');
            $table->integer('payroll_allowance_id');
            $table->integer('payroll_employee_id');
            $table->double('payroll_employee_allowance_amount');
            $table->tinyInteger('payroll_employee_allowance_archived');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_payroll_allowance_v2');
    }
}

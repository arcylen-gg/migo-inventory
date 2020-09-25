<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblPayrollDeduction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_payroll_deduction', function (Blueprint $table) {
            $table->increments('payroll_deduction_id');
            $table->integer('shop_id');
            $table->string('payroll_deduction_name');
            $table->double('payroll_deduction_amount', 18, 2);
            $table->double('payroll_monthly_amortization', 18, 2);
            $table->double('payroll_periodal_deduction', 18, 2);
            $table->date('payroll_deduction_date_filed');
            $table->date('payroll_deduction_date_start');
            $table->string('payroll_deduction_period');
            $table->string('payroll_deduction_category');
            $table->integer('payroll_deduction_type');
            $table->text('payroll_deduction_remarks');
            $table->tinyInteger('payroll_deduction_archived')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_payroll_deduction');
    }
}

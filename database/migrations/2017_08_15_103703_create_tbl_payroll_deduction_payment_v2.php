<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblPayrollDeductionPaymentV2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('tbl_payroll_deduction_payment_v2', function (Blueprint $table) {
            $table->increments('payroll_deduction_payment_id');
            $table->integer('payroll_deduction_id');
            $table->integer('payroll_employee_id');
            $table->integer('payroll_record_id');
            $table->integer('payroll_period_company_id');
            $table->string('deduction_name');
            $table->string('deduction_category');
            $table->date('payroll_payment_period');
            $table->double('payroll_beginning_balance', 18, 2);
            $table->double('payroll_payment_amount', 18, 2);
            $table->double('payroll_total_payment_amount', 18, 2);
            $table->double('payroll_remaining_balance', 18, 2);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_payroll_deduction_payment_v2');
    }
}
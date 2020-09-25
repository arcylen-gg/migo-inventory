<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblPayrollDeductionPayment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_payroll_deduction_payment', function (Blueprint $table) {
            $table->increments('payroll_deduction_payment_id');
            $table->integer('payroll_deduction_id');
            $table->integer('payroll_employee_id');
            $table->integer('payroll_record_id');
            $table->double('payroll_payment_amount', 18, 2);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_payroll_deduction_payment');
    }
}

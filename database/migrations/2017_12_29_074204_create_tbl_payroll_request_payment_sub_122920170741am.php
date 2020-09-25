<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblPayrollRequestPaymentSub122920170741am extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_payroll_request_payment_sub', function (Blueprint $table) 
        {
            $table->increments('payroll_request_payment_sub_id');
            $table->integer('payroll_request_payment_id')->unsigned();
            $table->foreign("payroll_request_payment_id", 'prp_id')->references('payroll_request_payment_id')->on('tbl_payroll_request_payment')->onDelete('cascade');
            $table->text('payroll_request_payment_sub_description');
            $table->double('payroll_request_payment_sub_amount');
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

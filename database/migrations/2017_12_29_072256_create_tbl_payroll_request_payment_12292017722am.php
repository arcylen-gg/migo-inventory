<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblPayrollRequestPayment12292017722am extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_payroll_request_payment', function (Blueprint $table) 
        {
            $table->increments('payroll_request_payment_id');
            $table->integer('payroll_employee_id')->unsigned();
            $table->foreign("payroll_employee_id")->references('payroll_employee_id')->on('tbl_payroll_employee_basic')->onDelete('cascade');
            $table->integer('payroll_approver_group_id')->unsigned();
            $table->foreign("payroll_approver_group_id", 'pag_payment_id')->references('payroll_approver_group_id')->on('tbl_payroll_approver_group')->onDelete('cascade');
            $table->string('payroll_request_payment_name');
            $table->date('payroll_request_payment_date');
            $table->text('payroll_request_payment_remark');
            $table->double('payroll_request_payment_total_amount');
            $table->string('payroll_request_payment_status')->default('pending');
            $table->integer('payroll_request_payment_status_level')->default(0);
            $table->integer('archived');
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

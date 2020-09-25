<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblPayrollApproverEmployee1272017158pm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_payroll_approver_employee', function (Blueprint $table)
        {
            $table->increments("payroll_approver_employee_id");
            $table->integer("payroll_employee_id")->unsigned();
            $table->foreign("payroll_employee_id")->references('payroll_employee_id')->on('tbl_payroll_employee_basic')->onDelete('cascade');
            $table->string("payroll_approver_employee_type");
            $table->integer("payroll_approver_employee_level");
            $table->integer("archived");         
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

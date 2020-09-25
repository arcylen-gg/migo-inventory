<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblPayrollLeaveActionReport extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
         Schema::create('tbl_payroll_leave_report', function (Blueprint $table) {
            $table->increments('payroll_leave_report_id');
            $table->string('payroll_employee_display_name',255);
            $table->integer('payroll_leave_employee_id')->unsigned();
            $table->integer('shop_id');
            $table->string('payroll_leave_action',100);
            $table->date('payroll_report_date_created');
            $table->decimal('payroll_leave_hours_remaining', 4, 2)->default('0.00');
            $table->decimal('payroll_leave_hours_accumulated', 4, 2)->default('0.00');
            $table->double('payroll_leave_cash_converted',18,2)->default('0.00');
            $table->tinyInteger('payroll_leave_report_archived');  
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
        Schema::drop('tbl_payroll_leave_report');  
    }
}

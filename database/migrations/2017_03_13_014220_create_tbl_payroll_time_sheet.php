<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblPayrollTimeSheet extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_payroll_time_sheet', function (Blueprint $table) {
            $table->increments('payroll_time_sheet_id');
            $table->integer('payroll_employee_id');
            $table->integer('payroll_company_id');
            $table->dateTime('payroll_time_in');
            $table->dateTime('payroll_time_out');
            $table->text('payroll_time_sheet_activities');
            $table->string('payroll_time_sheet_type')->default('Regular');
            $table->string('payroll_time_sheet_origin');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_payroll_time_sheet');
    }
}

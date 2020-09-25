<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblPayrollAllowanceRecord extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_payroll_allowance_record', function (Blueprint $table) {
            $table->increments('payroll_allowance_record_id');
            $table->integer('payroll_record_id');
            $table->integer('payroll_employee_id');
            $table->integer('payroll_employee_allowance_id');
            
            $table->double('payroll_record_allowance_amount', 18,2);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_payroll_allowance_record');
    }
}

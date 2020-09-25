<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblPayrollOvertimeRate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_payroll_overtime_rate', function (Blueprint $table) {
            $table->increments('payroll_overtime_rate_id');
            $table->integer('payroll_group_id');
            $table->string('payroll_overtime_name');
            $table->double('payroll_overtime_regular', 18, 2);
            $table->double('payroll_overtime_overtime', 18, 2);
            $table->double('payroll_overtime_nigth_diff', 18, 2);
            $table->double('payroll_overtime_rest_day', 18, 2);
            $table->double('payroll_overtime_rest_overtime', 18, 2);
            $table->double('payroll_overtime_rest_night', 18, 2);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_payroll_overtime_rate');
    }
}

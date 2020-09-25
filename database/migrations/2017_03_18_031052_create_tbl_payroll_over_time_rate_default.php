<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblPayrollOverTimeRateDefault extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_payroll_over_time_rate_default', function (Blueprint $table) {
            $table->increments('payroll_over_time_rate_default_id');
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
        Schema::drop('tbl_over_time_rate_default');
    }
}

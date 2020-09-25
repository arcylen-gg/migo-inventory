<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblPayrollGroupV4 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_payroll_group', function (Blueprint $table) {
            $table->string('payroll_under_time_category')->comment('Base on Salary, Custom, Not Deducted');
            $table->integer('payroll_under_time_interval')->default(0);
            $table->string('payroll_under_time_parameter')->comment('Second, Minute, Hour');
            $table->double('payroll_under_time_deduction', 18,2);
            $table->string('payroll_break_category')->comment('Base on Salary, Not Deducted');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_payroll_group', function (Blueprint $table) {
            //
        });
    }
}

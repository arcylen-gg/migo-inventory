<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblHolidayV256465 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_payroll_holiday_employee', function (Blueprint $table) {
            $table->increments('holiday_employee_id');
            $table->integer('payroll_company_id');
            $table->integer('payroll_employee_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_payroll_holiday_v2');
    }
}

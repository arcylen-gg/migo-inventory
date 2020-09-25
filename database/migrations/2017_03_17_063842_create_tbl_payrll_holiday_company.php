<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblPayrllHolidayCompany extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_payroll_holiday_company', function (Blueprint $table) {
            $table->increments('payroll_holiday_company_id');
            $table->integer('payroll_company_id');
            $table->integer('payroll_holiday_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_payroll_holiday_company');
    }
}

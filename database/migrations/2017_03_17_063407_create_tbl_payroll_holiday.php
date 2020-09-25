<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblPayrollHoliday extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_payroll_holiday', function (Blueprint $table) {
            $table->increments('payroll_holiday_id');
            $table->integer('shop_id');
            $table->string('payroll_holiday_name');
            $table->date('payroll_holiday_date');
            $table->string('payroll_holiday_category', 10)->default('special');
            $table->tinyInteger('payroll_holiday_archived');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_payroll_holiday');
    }
}

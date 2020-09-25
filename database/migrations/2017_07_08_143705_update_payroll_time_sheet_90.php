<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePayrollTimeSheet90 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_payroll_time_sheet', function (Blueprint $table)
        {
            $table->string('time_sheet_day_type')->default("regular");
            $table->string('is_holiday')->default("not_holiday");
            $table->double('time_sheet_daily_rate');
            $table->double('time_sheet_daily_cola_percentile');
            $table->double('time_sheet_daily_basic');
            $table->double('time_sheet_daily_cola');
            $table->double('time_sheet_daily_gross');
            $table->double('time_sheet_daily_gross_plus_cola');
            $table->double('time_sheet_daily_net')->default(0);
            $table->double('time_sheet_daily_total_addition');
            $table->double('time_sheet_daily_total_deduction');
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

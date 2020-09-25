<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblPayrollRecordV3 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_payroll_record', function (Blueprint $table) {
            $table->string('regular_hours');
            $table->string('late_overtime');
            $table->string('early_overtime');
            $table->string('late_hours');
            $table->string('under_time_hours');
            $table->string('rest_day_hours');
            $table->string('extra_day_hours');
            $table->string('total_hours');
            $table->string('night_differential');
            $table->string('special_holiday_hours');
            $table->string('regular_holiday_hours');
            $table->double('total_regular_days', 18, 2);
            $table->double('total_rest_days', 18, 2);
            $table->double('total_extra_days', 18, 2);
            $table->double('total_rh', 18, 2);
            $table->double('total_sh', 18, 2);
            $table->double('total_worked_days', 18, 2);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_payroll_record', function (Blueprint $table) {
            //
        });
    }
}

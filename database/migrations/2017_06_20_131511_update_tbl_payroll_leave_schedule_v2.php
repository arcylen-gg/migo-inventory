<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblPayrollLeaveScheduleV2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_payroll_leave_schedule', function (Blueprint $table) {
            $table->time('leave_hours')->default('00:00:00');
            $table->tinyInteger('leave_whole_day')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_payroll_leave_schedule', function (Blueprint $table) {
            //
        });
    }
}

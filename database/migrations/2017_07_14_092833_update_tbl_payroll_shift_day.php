<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblPayrollShiftDay extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_payroll_shift_day', function (Blueprint $table) {
            $table->tinyInteger('shift_flexi_time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::table('tbl_payroll_shift_day', function ($table) {
        $table->dropColumn(['shift_flexi_time']);
        });
    }
}

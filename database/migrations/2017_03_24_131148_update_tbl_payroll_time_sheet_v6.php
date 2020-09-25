<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblPayrollTimeSheetV6 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_payroll_time_sheet', function (Blueprint $table) {
            $table->string('payroll_time_sheet_type')->default('RG')->change();
            $table->dropColumn('payroll_time_approve_regular_overtime');
            $table->dropColumn('payroll_time_approve_early_overtime');
            $table->dropColumn('payroll_time_approve_extra_day');
            $table->dropColumn('payroll_time_approve_rest_day');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_payroll_time_sheet', function (Blueprint $table) {
            //
        });
    }
}

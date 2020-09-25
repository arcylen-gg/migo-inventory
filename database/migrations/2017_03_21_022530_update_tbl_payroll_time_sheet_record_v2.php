<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblPayrollTimeSheetRecordV2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_payroll_time_sheet_record', function (Blueprint $table) {
            $table->time('payroll_time_sheet_approved_in')->after('payroll_time_sheet_out');
            $table->time('payroll_time_sheet_approved_out')->after('payroll_time_sheet_approved_in');
            $table->integer('payroll_time_sheet_id')->unsigned()->change();
            $table->foreign('payroll_time_sheet_id')->references('payroll_time_sheet_id')->on('tbl_payroll_time_sheet')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_payroll_time_sheet_record', function (Blueprint $table) {
            //
        });
    }
}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblPayrollTimeSheetRecord extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_payroll_time_sheet_record', function (Blueprint $table) {
            $table->increments('payroll_time_sheet_record_id');
            $table->integer('payroll_time_sheet_id');
            $table->integer('payroll_company_id');
            $table->time('payroll_time_sheet_in');
            $table->time('payroll_time_sheet_out');
            $table->text('payroll_time_shee_activity');
            $table->string('payroll_time_sheet_origin');
            $table->string('payroll_time_sheet_status')->default('pending')->comment('status [pending, cancelled, processed]');
        });

        Schema::table('tbl_payroll_time_sheet', function(Blueprint $table){
            $table->dropColumn('payroll_company_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_payroll_time_sheet_record');
    }
}

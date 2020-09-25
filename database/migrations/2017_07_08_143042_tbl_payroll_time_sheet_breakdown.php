<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TblPayrollTimeSheetBreakdown extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_payroll_time_sheet_breakdown', function (Blueprint $table) 
        {
            $table->increments('payroll_time_sheet_breakdown_id');
            $table->integer('payroll_time_sheet_id')->unsigned();
            $table->string('time_sheet_breakdown_label');
            $table->string('time_sheet_breakdown_type');
            $table->time('time_sheet_breakdown_time');
            $table->double('time_sheet_breakdown_amount');
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
        //
    }
}

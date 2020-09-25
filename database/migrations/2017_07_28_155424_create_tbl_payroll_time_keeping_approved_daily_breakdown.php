<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblPayrollTimeKeepingApprovedDailyBreakdown extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_payroll_time_keeping_approved_daily_breakdown', function (Blueprint $table)
        {
            $table->increments("ptka_daily_breakdown_id");
            $table->integer("time_keeping_approve_id")->integer()->unsigned();
            $table->integer("payroll_time_sheet_id")->integer()->unsigned();
            $table->string("ptka_daily_type")->string()->default("additions");
            $table->string("ptka_daily_label")->string()->default("overtime");
            $table->double("ptka_daily_amount")->string()->default(0);
            $table->foreign('time_keeping_approve_id', 'tk_time_keeping_breakdown2')->references('time_keeping_approve_id')->on('tbl_payroll_time_keeping_approved')->onDelete('cascade');
            $table->foreign('payroll_time_sheet_id', 'tk_time_sheet_daily_breakdown')->references('payroll_time_sheet_id')->on('tbl_payroll_time_sheet')->onDelete('cascade');
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

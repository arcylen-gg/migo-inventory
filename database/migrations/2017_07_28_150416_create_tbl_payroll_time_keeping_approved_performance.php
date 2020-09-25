<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblPayrollTimeKeepingApprovedPerformance extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_payroll_time_keeping_approved_performance', function (Blueprint $table)
        {
            $table->increments("ptka_performance_id");
            $table->integer("time_keeping_approve_id")->integer()->unsigned();

            $table->string("ptka_daily_key");
            $table->double("ptka_daily_float");
            $table->string("ptka_daily_time");

            $table->foreign('time_keeping_approve_id', 'tk_time_keeping_breakdown3')->references('time_keeping_approve_id')->on('tbl_payroll_time_keeping_approved')->onDelete('cascade');
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

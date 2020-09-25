<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblPayrollTimeSheetManagement extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('tbl_payroll_time_sheet_management', function (Blueprint $table) {
            $table->increments('payroll_time_sheet_management_id');
            $table->integer('payroll_time_sheet_id');
            $table->string('payroll_time_sheet_approve_over_time')->default('00:00');
            $table->string('payroll_time_sheet_break')->default('00:00');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_payroll_time_sheet_management');
    }
}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblPayrollGroupRestDayV2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_payroll_group_rest_day', function (Blueprint $table) {

            $table->integer('payroll_group_id')->unsigned()->change();
            $table->foreign('payroll_group_id')->references('payroll_group_id')->on('tbl_payroll_group')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_payroll_group_rest_day', function (Blueprint $table) {
            //
        });
    }
}

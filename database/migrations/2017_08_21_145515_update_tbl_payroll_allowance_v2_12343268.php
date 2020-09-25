<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblPayrollAllowanceV212343268 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_payroll_allowance_v2', function (Blueprint $table) {
            $table->tinyInteger('basic_pay')->default(0);
            $table->tinyInteger('cola')->default(0);
            $table->tinyInteger('over_time_pay')->default(0);
            $table->tinyInteger('regular_holiday_pay')->default(0);
            $table->tinyInteger('special_holiday_pay')->default(0);
            $table->tinyInteger('leave_pay')->default(0);
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

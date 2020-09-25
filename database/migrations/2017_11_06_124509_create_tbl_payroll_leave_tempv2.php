<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblPayrollLeaveTempv2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('tbl_payroll_leave_tempv2', function (Blueprint $table) {
            $table->increments('payroll_leave_temp_id');
            $table->integer('shop_id');
            $table->integer('payroll_leave_type_id');
            $table->integer('payroll_leave_temp_days_cap');
            $table->tinyInteger('payroll_leave_temp_with_pay');
            $table->tinyInteger('payroll_leave_temp_is_cummulative'); 
            $table->tinyInteger('payroll_leave_temp_archived');  
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

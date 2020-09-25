<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblPayrollLeaveTemp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_payroll_leave_temp', function (Blueprint $table) {
            $table->increments('payroll_leave_temp_id');
            $table->integer('shop_id');
            $table->string('payroll_leave_temp_name',100);
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
        Schema::drop('tbl_payroll_leave_temp');  
    }
}

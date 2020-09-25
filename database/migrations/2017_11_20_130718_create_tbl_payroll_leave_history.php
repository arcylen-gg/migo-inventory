<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblPayrollLeaveHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
         Schema::create('tbl_payroll_leave_history', function (Blueprint $table) {
            $table->increments('payroll_leave_history_id');
            $table->integer('payroll_leave_employee_id')->unsigned();
            $table->integer('shop_id');
            $table->date('payroll_leave_date_created');
            $table->date('payroll_leave_date_applied');
            $table->decimal('consume', 4, 2)->default('0.00');
            $table->tinyInteger('payroll_leave_history_archived');  
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
         Schema::drop('tbl_payroll_leave_history');  
    }
}

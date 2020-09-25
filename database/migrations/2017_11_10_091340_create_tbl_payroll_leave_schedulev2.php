<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblPayrollLeaveSchedulev2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
            Schema::create('tbl_payroll_leave_schedulev2', function (Blueprint $table) {
            $table->increments('payroll_leave_schedule_id');
            $table->integer('payroll_leave_employee_id')->unsigned();
            $table->foreign("payroll_leave_employee_id")->references("payroll_leave_employee_id")->on("tbl_payroll_leave_employee_v2")->onDelete('cascade');
            $table->date('payroll_schedule_leave');
            $table->integer('shop_id');
            $table->time('leave_hours')->default('00:00');
            $table->tinyInteger('leave_whole_day')->default('1');
            $table->decimal('consume', 4, 2)->default('0.00');
            $table->string('notes',255);
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
        Schema::drop('tbl_payroll_leave_schedulev2');
    }
}

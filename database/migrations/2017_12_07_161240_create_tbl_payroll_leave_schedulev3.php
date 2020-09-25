<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblPayrollLeaveSchedulev3 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('tbl_payroll_leave_schedulev3', function (Blueprint $table) {
            $table->increments('payroll_leave_schedule_id');
            $table->integer('payroll_employee_id_reliever');
            $table->integer('payroll_employee_id_approver');
            $table->integer('payroll_employee_id');
            $table->date('payroll_schedule_leave');
            $table->date('date_filed');
            $table->integer('shop_id');
            $table->time('leave_hours')->default('00:00');
            $table->tinyInteger('leave_whole_day')->default('1');
            $table->decimal('consume', 4, 2)->default('0.00');
            $table->string('remarks',255);
            $table->string('status',255);
            $table->string('payroll_leave_name',255);
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

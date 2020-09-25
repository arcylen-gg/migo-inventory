<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblPayrollShift extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_payroll_shift', function (Blueprint $table) {
            $table->increments('payroll_shift_id');
            $table->integer('payroll_group_id')->unsigned();
            $table->foreign('payroll_group_id')->references('payroll_group_id')->on('tbl_payroll_group')->onDelete('cascade');
            $table->string('day');
            $table->double('target_hours', 18, 2);
            $table->time('work_start');
            $table->time('work_end');
            $table->time('break_start');
            $table->time('break_end');
            $table->tinyInteger('flexi');
            $table->tinyInteger('rest_day');
            $table->tinyInteger('extra_day');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_payroll_shift');
    }
}

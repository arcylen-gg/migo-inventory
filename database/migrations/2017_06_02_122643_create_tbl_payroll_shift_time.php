<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblPayrollShiftTime extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_payroll_shift_time', function (Blueprint $table) 
        {
            $table->increments('shift_time_id');
            $table->integer('shift_day_id')->unsigned();
            $table->time('shift_work_start');
            $table->time('shift_work_end');
            $table->foreign('shift_day_id')->references('shift_day_id')->on('tbl_payroll_shift_day')->onDelete('cascade');
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

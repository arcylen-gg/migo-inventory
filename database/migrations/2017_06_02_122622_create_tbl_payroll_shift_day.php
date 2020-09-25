<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblPayrollShiftDay extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_payroll_shift_day', function (Blueprint $table) 
        {
            $table->increments('shift_day_id');
            $table->string('shift_day');
            $table->integer('shift_target_hours');
            $table->tinyInteger("shift_rest_day");
            $table->tinyInteger("shift_extra_day");
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

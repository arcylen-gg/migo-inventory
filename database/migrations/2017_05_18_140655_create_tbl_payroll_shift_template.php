<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblPayrollShiftTemplate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_payroll_shift_template', function (Blueprint $table) {
            $table->increments('shift_template_id');
            $table->integer('shift_code_id')->unsigned();
            $table->foreign('shift_code_id')->references('shift_code_id')->on('tbl_payroll_shift_code')->onDelete('cascade');
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
        Schema::drop('tbl_payroll_shift_template');
    }
}

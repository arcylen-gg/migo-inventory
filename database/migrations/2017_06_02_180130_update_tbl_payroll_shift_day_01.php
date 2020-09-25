<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblPayrollShiftDay01 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_payroll_shift_day', function (Blueprint $table) 
        {
            $table->integer('shift_code_id')->unsigned();
            $table->foreign('shift_code_id')->references('shift_code_id')->on('tbl_payroll_shift_code')->onDelete('cascade');
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

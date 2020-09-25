<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdatePayrollShiftTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_payroll_shift', function (Blueprint $table) {
            $table->tinyInteger('night_shift')->default(0);
        });
        Schema::table('tbl_payroll_shift_template', function (Blueprint $table) {
            $table->tinyInteger('night_shift')->default(0);
        });
        Schema::table('tbl_payroll_employee_schedule', function (Blueprint $table) {
            $table->tinyInteger('night_shift')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_payroll_shift', function (Blueprint $table) {
            //
        });
    }
}

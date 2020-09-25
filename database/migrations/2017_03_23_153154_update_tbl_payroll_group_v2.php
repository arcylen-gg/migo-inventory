<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblPayrollGroupV2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_payroll_group', function (Blueprint $table) {
            $table->dropColumn('payroll_group_break');
            $table->time('payroll_group_break_start');
            $table->time('payroll_group_break_end');
            $table->tinyInteger('payroll_group_is_flexi_break')->default(0);
            $table->integer('payroll_group_flexi_break');
            $table->string('payroll_late_category');
            $table->integer('payroll_late_interval');
            $table->string('payroll_late_parameter');
            $table->double('payroll_late_deduction');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_payroll_group', function (Blueprint $table) {
            //
        });
    }
}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModTblPayrollLeaveTempDaysCap extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_payroll_leave_temp', function ($table) {
            $table->decimal('payroll_leave_temp_days_cap', 4, 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_payroll_leave_temp', function ($table) {
            $table->integer('payroll_leave_temp_days_cap')->change();
        });    
    }
}

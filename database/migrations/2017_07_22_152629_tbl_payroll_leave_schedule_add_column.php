<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TblPayrollLeaveScheduleAddColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_payroll_leave_schedule', function ($table) {
            $table->decimal('beg_hour', 4, 2);
            $table->decimal('end_hour', 4, 2)->default(0);
            $table->decimal('consume', 4, 2)->default(0);
            $table->string('notes')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_payroll_leave_schedule', function ($table) {
            $table->dropColumn(['beg_hour', 'end_hour', 'consume', 'notes']);
        });    
    }
}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblPayrollRecordV4 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_payroll_record', function (Blueprint $table) {
            $table->integer('absent_count');
            $table->double('absent_deduction', 18, 2);
            $table->integer('leave_count');
            $table->double('leave_amount', 18,2);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_payroll_record', function (Blueprint $table) {
            //
        });
    }
}

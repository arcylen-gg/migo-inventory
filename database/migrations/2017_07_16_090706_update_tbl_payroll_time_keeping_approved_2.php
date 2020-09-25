<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblPayrollTimeKeepingApproved2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_payroll_time_keeping_approved', function (Blueprint $table) {
            $table->double('sss_salary');
            $table->double('sss_ee');
            $table->double('sss_er');
            $table->double('sss_ec');
            $table->double('phihealth_salary');
            $table->double('philhealth_ee');
            $table->double('philhealth_er');
            $table->double('pagibig_salary');
            $table->double('pagibig_ee');
            $table->double('pagibig_er');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_payroll_time_keeping_approved', function (Blueprint $table) {
            //
        });
    }
}

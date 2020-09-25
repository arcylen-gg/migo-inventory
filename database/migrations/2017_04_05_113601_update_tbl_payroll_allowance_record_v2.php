<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblPayrollAllowanceRecordV2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_payroll_allowance_record', function (Blueprint $table) {
            $table->integer('payroll_record_id')->unsigned()->change();
            $table->foreign("payroll_record_id")->references("payroll_record_id")->on("tbl_payroll_record")->onDelete('cascade');
            $table->integer('payroll_employee_id')->unsigned()->change();
            $table->foreign("payroll_employee_id")->references("payroll_employee_id")->on("tbl_payroll_employee_basic")->onDelete('cascade');
            $table->dropColumn('payroll_employee_allowance_id');
            $table->integer('payroll_allowance_id')->unsigned();
            $table->foreign("payroll_allowance_id")->references("payroll_allowance_id")->on("tbl_payroll_allowance")->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_payroll_allowance_record', function (Blueprint $table) {
            //
        });
    }
}

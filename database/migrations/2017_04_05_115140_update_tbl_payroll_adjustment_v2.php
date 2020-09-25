<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblPayrollAdjustmentV2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_payroll_adjustment', function (Blueprint $table) {
            $table->integer('payroll_employee_id')->unsigned()->change();
            $table->foreign("payroll_employee_id")->references("payroll_employee_id")->on("tbl_payroll_employee_basic")->onDelete('cascade');
            $table->integer('payroll_period_company_id')->unsigned()->change();
            $table->foreign("payroll_period_company_id")->references("payroll_period_company_id")->on("tbl_payroll_period_company")->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_payroll_adjustment', function (Blueprint $table) {
            //
        });
    }
}

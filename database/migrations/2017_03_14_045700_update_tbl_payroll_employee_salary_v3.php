<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblPayrollEmployeeSalaryV3 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_payroll_employee_salary', function (Blueprint $table) {
            $table->double('payroll_employee_salary_cola', 18, 2)->default(0)->after('payroll_employee_salary_daily');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_payroll_employee_salary', function (Blueprint $table) {
            //
        });
    }
}

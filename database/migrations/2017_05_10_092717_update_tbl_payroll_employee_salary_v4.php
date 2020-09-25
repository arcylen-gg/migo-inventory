<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblPayrollEmployeeSalaryV4 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_payroll_employee_salary', function (Blueprint $table) {
            $table->tinyInteger('is_deduct_tax_default')->default(1);
            $table->double('deduct_tax_custom',18,2);
            $table->tinyInteger('is_deduct_sss_default')->default(1);
            $table->double('deduct_sss_custom',18,2);
            $table->tinyInteger('is_deduct_philhealth_default')->default(1);
            $table->double('deduct_philhealth_custom',18,2);
            $table->tinyInteger('is_deduct_pagibig_default')->default(1);
            $table->double('deduct_pagibig_custom',18,2);

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

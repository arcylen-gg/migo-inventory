<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblPayrollAllowanceV2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_payroll_allowance', function (Blueprint $table) {
            $table->string('payroll_allowance_add_period')->default('Every Period')->after('payroll_allowance_category');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_payroll_allowance', function (Blueprint $table) {
            //
        });
    }
}

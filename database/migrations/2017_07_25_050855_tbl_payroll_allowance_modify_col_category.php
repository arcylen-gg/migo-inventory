<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TblPayrollAllowanceModifyColCategory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_payroll_allowance', function (Blueprint $table) {
            $table->string('payroll_allowance_category', 20)->change();
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
            $table->string('payroll_allowance_category', 10)->change();
        });
    }
}

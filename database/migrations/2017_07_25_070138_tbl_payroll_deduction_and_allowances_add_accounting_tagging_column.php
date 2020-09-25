<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TblPayrollDeductionAndAllowancesAddAccountingTaggingColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_payroll_allowance', function (Blueprint $table) {
            $table->integer('expense_account_id');
        });
         Schema::table('tbl_payroll_deduction', function (Blueprint $table) {
            $table->integer('expense_account_id');
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
            $table->dropColumn('expense_account_id');
        });
        Schema::table('tbl_payroll_deduction', function (Blueprint $table) {
            $table->dropColumn('expense_account_id');
        });    
    }
}

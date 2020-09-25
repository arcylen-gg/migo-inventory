<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblPayrollAccountCombination extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_payroll_account_combination', function (Blueprint $table) {
            $table->increments('payroll_account_combination_id');
            $table->integer("payroll_account_id")->unsigned();
            $table->foreign("payroll_account_id")->references("payroll_account_id")->on("tbl_payroll_account")->onDelete('cascade');
            $table->integer("payroll_employee_id")->unsigned();
            $table->foreign("payroll_employee_id")->references("payroll_employee_id")->on("tbl_payroll_employee_basic")->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_payroll_account_combination');
    }
}

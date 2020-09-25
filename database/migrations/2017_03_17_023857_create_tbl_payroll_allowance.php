<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblPayrollAllowance extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_payroll_allowance', function (Blueprint $table) {
            $table->increments('payroll_allowance_id');
            $table->integer('shop_id');
            $table->string('payroll_allowance_name');
            $table->double('payroll_allowance_amount', 18, 2);
            $table->string('payroll_allowance_category', 10)->default('fixed');
            $table->tinyInteger('payroll_allowance_archived');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_payroll_allowance');
    }
}

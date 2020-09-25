<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblPayrollDeductionType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_payroll_deduction_type', function (Blueprint $table) {
            $table->increments('payroll_deduction_type_id');
            $table->integer('shop_id');
            $table->string('payroll_deduction_category');
            $table->string('payroll_deduction_type_name');
            $table->tinyInteger('payroll_deduction_archived');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_payroll_deduction_type');
    }
}

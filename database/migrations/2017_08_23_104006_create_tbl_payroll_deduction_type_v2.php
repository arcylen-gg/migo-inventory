<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblPayrollDeductionTypeV2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('tbl_payroll_deduction_type_v2', function (Blueprint $table) {
            $table->increments('payroll_deduction_type_id');
            $table->integer('shop_id');
            $table->string('payroll_deduction_category');
            $table->string('payroll_deduction_type_name');
            $table->tinyInteger('payroll_deduction_archived')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}

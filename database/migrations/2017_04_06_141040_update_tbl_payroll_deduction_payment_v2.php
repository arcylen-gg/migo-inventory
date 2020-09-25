<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblPayrollDeductionPaymentV2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_payroll_deduction_payment', function (Blueprint $table) {
            $table->string('deduction_name');
            $table->string('deduction_category');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_payroll_deduction_payment', function (Blueprint $table) {
            //
        });
    }
}

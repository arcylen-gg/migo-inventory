<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblPayrollAdjustment934PM extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_payroll_adjustment', function (Blueprint $table)
        {
            $table->text("adjustment_setting");
            $table->tinyInteger("add_gross_pay");
            $table->tinyInteger("deduct_gross_pay");
            $table->tinyInteger("add_taxable_salary");
            $table->tinyInteger("deduct_taxable_salary");
            $table->tinyInteger("add_net_pay");
            $table->tinyInteger("deduct_net_pay");
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

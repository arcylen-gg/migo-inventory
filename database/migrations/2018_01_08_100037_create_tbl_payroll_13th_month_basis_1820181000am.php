<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblPayroll13thMonthBasis1820181000am extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('tbl_payroll_13th_month_basis', function (Blueprint $table) 
         {
            $table->increments("payroll_13th_month_basis_id");
            $table->integer("payroll_group_id")->unsigned();
            $table->foreign("payroll_group_id")->references('payroll_group_id')->on('tbl_payroll_group')->onDelete('cascade');
            $table->string("payroll_group_13month_basis");
            $table->tinyInteger("payroll_group_13th_month_addition_allowance")->default(0);
            $table->tinyInteger("payroll_group_13th_month_addition_de_minimis_benefit")->default(0);
            $table->tinyInteger("payroll_group_13th_month_addition_special_holiday")->default(0);
            $table->tinyInteger("payroll_group_13th_month_addition_regular_holiday")->default(0);
            $table->tinyInteger("payroll_group_13th_month_addition_cola")->default(0);
            $table->tinyInteger("payroll_group_13th_month_addition_late")->default(0);
            $table->tinyInteger("payroll_group_13th_month_addition_undertime")->default(0);
            $table->tinyInteger("payroll_group_13th_month_addition_absent")->default(0);
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

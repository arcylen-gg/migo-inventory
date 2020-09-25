<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblPayrollTimeKeepingApprovedBreakdown extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_payroll_time_keeping_approved_breakdown', function (Blueprint $table)
        {
            $table->increments("ptkab_id");
            $table->integer("time_keeping_approve_id")->integer()->unsigned();
            $table->string("ptkab_label");
            $table->text("ptkab_description");
            $table->double("ptkab_amount");
            $table->tinyInteger("add_gross_pay")->default(0);
            $table->tinyInteger("deduct_gross_pay")->default(0);
            $table->tinyInteger("add_taxable_salary")->default(0);
            $table->tinyInteger("deduct_taxable_salary")->default(0);
            $table->tinyInteger("add_net_pay")->default(0);
            $table->tinyInteger("deduct_net_pay")->default(0);
            $table->foreign('time_keeping_approve_id', 'tk_time_keeping_breakdown')->references('time_keeping_approve_id')->on('tbl_payroll_time_keeping_approved')->onDelete('cascade');
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

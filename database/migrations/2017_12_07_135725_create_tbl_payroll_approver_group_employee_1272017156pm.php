<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblPayrollApproverGroupEmployee1272017156pm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_payroll_approver_group_employee', function (Blueprint $table)
        {
            $table->increments("payroll_approver_group_employee_id");
            $table->integer("payroll_approver_group_id")->unsigned();
            $table->foreign("payroll_approver_group_id", 'pag_id_foreign')->references('payroll_approver_group_id')->on('tbl_payroll_approver_group')->onDelete('cascade');
            $table->integer("payroll_approver_employee_id")->unsigned();
            $table->foreign("payroll_approver_employee_id", 'pae_id_foreign')->references('payroll_approver_employee_id')->on('tbl_payroll_approver_employee')->onDelete('cascade');
            $table->integer("payroll_approver_group_level");
        });
    }

    /*
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}

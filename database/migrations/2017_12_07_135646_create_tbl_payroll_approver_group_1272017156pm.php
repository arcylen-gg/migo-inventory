<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblPayrollApproverGroup1272017156pm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_payroll_approver_group', function (Blueprint $table)
        {
            $table->increments("payroll_approver_group_id");
            $table->string("payroll_approver_group_name");
            $table->string("payroll_approver_group_type"); 
            $table->integer("archived");            
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

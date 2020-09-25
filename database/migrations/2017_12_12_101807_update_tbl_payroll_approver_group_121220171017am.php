<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTblPayrollApproverGroup121220171017am extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tbl_payroll_approver_group', function (Blueprint $table) {
            $table->integer('shop_id')->default(0)->after('payroll_approver_group_id');
            $table->integer('payroll_approver_group_level')->after('payroll_approver_group_type');
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

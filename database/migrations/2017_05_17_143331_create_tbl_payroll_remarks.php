<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTblPayrollRemarks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_payroll_remarks', function (Blueprint $table) {
            $table->increments('payroll_remarks_id');
            $table->integer('shop_id')->unsigned();
            $table->foreign('shop_id')->references('shop_id')->on('tbl_shop')->onDelete('cascade');
            $table->integer('user_id')->comment('connected to tbl_user');
            $table->integer('payroll_period_company_id')->unsigned();
            $table->foreign('payroll_period_company_id')->references('payroll_period_company_id')->on('tbl_payroll_period_company')->onDelete('cascade');
            $table->text('payroll_remarks');
            $table->string('payroll_type')->default('text');
            $table->string('file_name')->default('');
            $table->dateTime('payroll_remarks_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tbl_payroll_remarks');
    }
}
